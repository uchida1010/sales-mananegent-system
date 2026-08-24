import { act, renderHook, waitFor } from "@testing-library/react";
import { createElement, type ReactNode } from "react";
import { MemoryRouter } from "react-router";

import type {
  rolesIndex as rolesIndexRequest,
  userIndex as userIndexRequest,
} from "~/api/salesManagementSystem";

import { useUserSearch, type UserSearchForm } from "./useUserSearch";

const mocks = vi.hoisted(() => ({
  userIndex: vi.fn<typeof userIndexRequest>(),
  rolesIndex: vi.fn<typeof rolesIndexRequest>(),
}));

vi.mock("~/api/salesManagementSystem", () => ({
  userIndex: mocks.userIndex,
  rolesIndex: mocks.rolesIndex,
}));

const userIndexResponse = {
  data: [
    {
      userCode: 10,
      name: "山田太郎",
      nameKana: "やまだたろう",
      email: "taro@example.com",
      status: "active",
      roles: ["admin"],
    },
  ],
  links: {
    first: null,
    last: null,
    prev: null,
    next: null,
  },
  meta: {
    current_page: 2,
    from: 1,
    last_page: 5,
    links: [],
    path: null,
    per_page: 10,
    to: 1,
    total: 41,
  },
};

const rolesIndexResponse = {
  data: [
    {
      id: 3,
      name: "営業",
    },
  ],
};

const renderUseUserSearch = (initialEntries = ["/user"]) =>
  renderHook(() => useUserSearch(), {
    wrapper: ({ children }: { children: ReactNode }) =>
      createElement(MemoryRouter, { initialEntries }, children),
  });

describe("useUserSearch", () => {
  beforeEach(() => {
    vi.clearAllMocks();
    mocks.userIndex.mockResolvedValue(userIndexResponse);
    mocks.rolesIndex.mockResolvedValue(rolesIndexResponse);
  });

  it("URLクエリを初期検索条件として読み込み、ユーザーと権限を取得する", async () => {
    const { result } = renderUseUserSearch([
      "/user?keyword=山田&userCode=10&email=taro%40example.com&roleId=3&activeOnly=1&page=2",
    ]);

    await waitFor(() => {
      expect(mocks.userIndex).toHaveBeenCalledWith({
        keyword: "山田",
        userCode: "10",
        email: "taro@example.com",
        roleId: "3",
        activeOnly: "1",
        page: "2",
      });
    });

    await waitFor(() => {
      expect(result.current.totalPages).toBe(5);
    });

    expect(mocks.rolesIndex).toHaveBeenCalledWith();
    expect(result.current.searchForm).toEqual({
      keyword: "山田",
      userCode: "10",
      email: "taro@example.com",
      roleId: "3",
      activeOnly: true,
    });
    expect(result.current.page).toBe(2);
    expect(result.current.users).toEqual(userIndexResponse.data);
    expect(result.current.roles).toEqual(rolesIndexResponse.data);
  });

  it("検索条件をURLクエリ用の値に変換して検索し、ページを1に戻す", async () => {
    const { result } = renderUseUserSearch(["/user?page=4"]);

    await waitFor(() => {
      expect(mocks.userIndex).toHaveBeenCalledWith({ page: "4" });
    });

    const searchForm: UserSearchForm = {
      keyword: "佐藤",
      userCode: "25",
      email: "sato@example.com",
      roleId: "2",
      activeOnly: true,
    };

    act(() => {
      result.current.setSearchForm(searchForm);
    });

    act(() => {
      result.current.handleSearch();
    });

    await waitFor(() => {
      expect(mocks.userIndex).toHaveBeenLastCalledWith({
        keyword: "佐藤",
        userCode: "25",
        email: "sato@example.com",
        roleId: "2",
        activeOnly: "1",
        page: "1",
      });
    });

    expect(result.current.page).toBe(1);
    expect(result.current.searchForm).toEqual(searchForm);
  });
});

it("空の検索条件はpage以外のAPIパラメータに含めない", async () => {
  renderUseUserSearch(["/user?keyword=&userCode=&email=&roleId=&page=1"]);

  await waitFor(() => {
    expect(mocks.userIndex).toHaveBeenCalledWith({
      page: "1",
    });
  });
});

it("ページ変更時は現在の検索条件を維持して指定ページを取得する", async () => {
  const { result } = renderUseUserSearch(["/user?keyword=山田&roleId=3&activeOnly=1&page=1"]);

  await waitFor(() => {
    expect(mocks.userIndex).toHaveBeenCalledWith({
      keyword: "山田",
      roleId: "3",
      activeOnly: "1",
      page: "1",
    });
  });

  act(() => {
    result.current.handlePageChange(3);
  });

  await waitFor(() => {
    expect(mocks.userIndex).toHaveBeenLastCalledWith({
      activeOnly: "1",
      keyword: "山田",
      page: "3",
      roleId: "3",
    });
  });

  expect(result.current.page).toBe(3);
});

it("検索条件をクリアすると初期条件で再取得する", async () => {
  const { result } = renderUseUserSearch(["/user?keyword=山田&activeOnly=1&page=3"]);

  await waitFor(() => {
    expect(mocks.userIndex).toHaveBeenCalledWith({
      keyword: "山田",
      activeOnly: "1",
      page: "3",
    });
  });

  act(() => {
    result.current.clearSearch();
  });

  await waitFor(() => {
    expect(mocks.userIndex).toHaveBeenLastCalledWith({
      page: "1",
    });
  });

  expect(result.current.searchForm).toEqual({
    keyword: "",
    userCode: "",
    email: "",
    roleId: "",
    activeOnly: false,
  });
  expect(result.current.page).toBe(1);
});
