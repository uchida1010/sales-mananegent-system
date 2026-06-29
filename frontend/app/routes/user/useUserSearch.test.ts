import { act, renderHook, waitFor } from "@testing-library/react";
import { createElement, type ReactNode } from "react";
import { MemoryRouter } from "react-router";

import { useUserSearch, type UserSearchForm } from "./useUserSearch";

const mocks = vi.hoisted(() => ({
  userIndex: vi.fn(),
  rolesIndex: vi.fn(),
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