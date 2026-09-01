import { act, renderHook } from "@testing-library/react";
import { createElement, type ReactNode } from "react";
import { MemoryRouter, useLocation } from "react-router";

import {
  buildSearchParams,
  buildUserIndexParams,
  parseUserSearchParams,
  toSearchForm,
  useUserSearch,
  type UserSearchData,
} from "./useUserSearch";

const loaderData: UserSearchData = {
  users: [],
  roles: [],
  page: 2,
  totalPages: 5,
  searchForm: {
    keyword: "山田",
    userCode: "10",
    email: "taro@example.com",
    roleId: "3",
    activeOnly: true,
  },
};

const createWrapper = (initialEntry = "/user") => {
  return function Wrapper({ children }: { children: ReactNode }) {
    return createElement(MemoryRouter, { initialEntries: [initialEntry] }, children);
  };
};

const renderUserSearch = (initialEntry?: string) =>
  renderHook(
    () => ({
      userSearch: useUserSearch(loaderData),
      location: useLocation(),
    }),
    { wrapper: createWrapper(initialEntry) },
  );

describe("ユーザー検索パラメータ", () => {
  it("URLクエリを検索条件へ変換する", () => {
    const params = parseUserSearchParams(
      new URLSearchParams(
        "keyword=山田&userCode=10&email=taro%40example.com&roleId=3&activeOnly=1&page=2",
      ),
    );

    expect(params).toEqual({
      keyword: "山田",
      userCode: "10",
      email: "taro@example.com",
      roleId: "3",
      activeOnly: true,
      page: 2,
    });
    expect(toSearchForm(params)).toEqual({
      keyword: "山田",
      userCode: "10",
      email: "taro@example.com",
      roleId: "3",
      activeOnly: true,
    });
  });

  it("空の検索条件はAPIパラメータに含めない", () => {
    expect(buildUserIndexParams(parseUserSearchParams(new URLSearchParams()))).toEqual({
      page: "1",
    });
  });

  it("検索条件とページ番号をURLクエリ用の値に変換する", () => {
    expect(
      buildSearchParams(
        {
          keyword: "佐藤",
          userCode: "25",
          email: "sato@example.com",
          roleId: "2",
          activeOnly: true,
        },
        3,
      ),
    ).toEqual({
      keyword: "佐藤",
      userCode: "25",
      email: "sato@example.com",
      roleId: "2",
      activeOnly: "1",
      page: "3",
    });
  });
});

describe("useUserSearch", () => {
  it("loader dataと初期検索フォームを返す", () => {
    const { result } = renderUserSearch();

    expect(result.current.userSearch).toMatchObject(loaderData);
  });

  it("フォームを更新して検索すると検索条件をURLへ反映し、1ページ目に戻す", () => {
    const { result } = renderUserSearch();

    act(() => {
      result.current.userSearch.setSearchForm({
        keyword: "佐藤",
        userCode: "25",
        email: "sato@example.com",
        roleId: "2",
        activeOnly: false,
      });
    });
    act(() => {
      result.current.userSearch.handleSearch();
    });

    expect(result.current.location.search).toBe(
      "?keyword=%E4%BD%90%E8%97%A4&userCode=25&email=sato%40example.com&roleId=2&page=1",
    );
  });

  it("現在の検索条件を維持してページを変更する", () => {
    const { result } = renderUserSearch();

    act(() => {
      result.current.userSearch.handlePageChange(4);
    });

    expect(result.current.location.search).toBe(
      "?keyword=%E5%B1%B1%E7%94%B0&userCode=10&email=taro%40example.com&roleId=3&activeOnly=1&page=4",
    );
  });

  it("検索条件をクリアする", () => {
    const { result } = renderUserSearch("/user?keyword=山田&page=2");

    act(() => {
      result.current.userSearch.clearSearch();
    });

    expect(result.current.location.search).toBe("");
  });
});
