import { MantineProvider } from "@mantine/core";
import { render, screen, waitFor } from "@testing-library/react";
import { MemoryRouter } from "react-router";

import type {
  rolesIndex as rolesIndexRequest,
  userIndex as userIndexRequest,
} from "~/api/salesManagementSystem";

import UserIndex, { clientLoader } from "./index";
import type { Route } from "./+types/index";

const mocks = vi.hoisted(() => ({
  userIndex: vi.fn<typeof userIndexRequest>(),
  rolesIndex: vi.fn<typeof rolesIndexRequest>(),
}));

vi.mock("~/api/salesManagementSystem", () => ({
  userIndex: mocks.userIndex,
  rolesIndex: mocks.rolesIndex,
}));

const renderUserIndex = () => {
  return render(
    <MantineProvider>
      <MemoryRouter>
        <UserIndex
          loaderData={{
            searchKey: "",
            searchForm: {
              keyword: "",
              userCode: "",
              email: "",
              roleId: "",
              activeOnly: false,
            },
            users: [],
            roles: [],
            page: 1,
            totalPages: 1,
          }}
        />
      </MemoryRouter>
    </MantineProvider>,
  );
};

describe("UserIndex", () => {
  beforeEach(() => {
    vi.clearAllMocks();

    mocks.userIndex.mockResolvedValue({
      data: [],
      links: {
        first: null,
        last: null,
        prev: null,
        next: null,
      },
      meta: {
        current_page: 1,
        from: null,
        last_page: 1,
        links: [],
        path: null,
        per_page: 0,
        to: null,
        total: 0,
      },
    });
    mocks.rolesIndex.mockResolvedValue({ data: [] });
  });

  it("loads users and roles from the URL query", async () => {
    await clientLoader({
      request: new Request("http://localhost/user?keyword=山田&activeOnly=1&page=2"),
    } as Route.ClientLoaderArgs);
  });

  it("renders the initial user list screen", async () => {
    renderUserIndex();

    expect(screen.getByRole("heading", { name: "ユーザー一覧" })).toBeInTheDocument();
    expect(screen.getByPlaceholderText("名前、よみがなで検索")).toBeInTheDocument();
    expect(screen.getByRole("columnheader", { name: "ユーザーID" })).toBeInTheDocument();
    expect(screen.getByText("E-mail")).toBeInTheDocument();
    expect(screen.getByRole("button", { name: "検索" })).toBeInTheDocument();
  });
});
