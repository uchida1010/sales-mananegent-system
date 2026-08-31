import { act, renderHook, waitFor } from "@testing-library/react";
import { createElement, type ReactNode } from "react";
import { MemoryRouter, useLocation } from "react-router";

import type {
  rolesIndex as rolesIndexRequest,
  userIndex as userIndexRequest,
} from "~/api/salesManagementSystem";

import { useUserSearch, type UserSearchData, type UserSearchForm } from "./useUserSearch";

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
const renderUseUserSearch = (
  initialEntries: string[],
  data: UserSearchData,
) =>
  renderHook(
    () => {
      const userSearch = useUserSearch(data);
      const location = useLocation();

      return {
        ...userSearch,
        location,
      };
    },
    {
      wrapper: ({ children }: { children: ReactNode }) =>
        createElement(MemoryRouter, { initialEntries }, children),
    },
  );
describe("useUserSearch", () => {
  beforeEach(() => {
    vi.clearAllMocks();
    mocks.userIndex.mockResolvedValue(userIndexResponse);
    mocks.rolesIndex.mockResolvedValue(rolesIndexResponse);
  });

  it("検索条件をURLクエリ用の値に変換して検索し、ページを1に戻す", async () => {
    const { result } = renderUseUserSearch(
      ["/user?page=4"],
      {
        users: [],
        roles: [],
        page: 4,
        totalPages: 1,
        searchForm: {
          keyword: "",
          userCode: "",
          email: "",
          roleId: "",
          activeOnly: false,
        },
      },
    );

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

    const params = new URLSearchParams(result.current.location.search);

    expect(params.get("keyword")).toBe("佐藤");
    expect(params.get("userCode")).toBe("25");
    expect(params.get("email")).toBe("sato@example.com");
    expect(params.get("roleId")).toBe("2");
    expect(params.get("activeOnly")).toBe("1");
    expect(params.get("page")).toBe("1");

    expect(result.current.searchForm).toEqual(searchForm);
  });
});

it("空の検索条件はURLクエリに含めない", () => {
  const { result } = renderUseUserSearch(
    ["/user"],
    {
      users: [],
      roles: [],
      page: 1,
      totalPages: 1,
      searchForm: {
        keyword: "",
        userCode: "",
        email: "",
        roleId: "",
        activeOnly: false,
      },
    },
  );

  act(() => {
    result.current.handleSearch();
  });

  const params = new URLSearchParams(result.current.location.search);

  expect(params.get("keyword")).toBeNull();
  expect(params.get("userCode")).toBeNull();
  expect(params.get("email")).toBeNull();
  expect(params.get("roleId")).toBeNull();
  expect(params.get("activeOnly")).toBeNull();
  expect(params.get("page")).toBe("1");
});

it("ページ変更時は現在の検索条件を維持してURLのページを変更する", async () => {
  const { result } = renderUseUserSearch(
    ["/user?keyword=山田&roleId=3&activeOnly=1&page=1"],
    {
      users: [],
      roles: [],
      page: 1,
      totalPages: 1,
      searchForm: {
        keyword: "山田",
        userCode: "",
        email: "",
        roleId: "3",
        activeOnly: true,
      },
    },
  );

  act(() => {
    result.current.handlePageChange(3);
  });

  const params = new URLSearchParams(result.current.location.search);

  expect(params.get("page")).toBe("3");
});

it("検索条件をクリアするとURLクエリを削除する", async () => {
  const { result } = renderUseUserSearch(
    ["/user?keyword=山田&activeOnly=1&page=3"],
    {
      users: [],
      roles: [],
      page: 1,
      totalPages: 1,
      searchForm: {
        keyword: "山田",
        userCode: "",
        email: "",
        roleId: "",
        activeOnly: true,
      },
    },
  );

  act(() => {
    result.current.clearSearch();
  });
});
