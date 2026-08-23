import { MantineProvider } from "@mantine/core";
import { render, screen, waitFor } from "@testing-library/react";
import { MemoryRouter } from "react-router";

import UserIndex from "./index";

const mocks = vi.hoisted(() => ({
  userIndex: vi.fn(),
  rolesIndex: vi.fn(),
}));

vi.mock("~/api/salesManagementSystem", () => ({
  userIndex: mocks.userIndex,
  rolesIndex: mocks.rolesIndex,
}));

const renderUserIndex = () => {
  return render(
    <MantineProvider>
      <MemoryRouter>
        <UserIndex />
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

  it("mounts and loads the initial user list", async () => {
    renderUserIndex();

    expect(screen.getByRole("heading", { name: "ユーザー一覧" })).toBeInTheDocument();
    expect(screen.getByPlaceholderText("名前、よみがなで検索")).toBeInTheDocument();
    expect(screen.getByRole("columnheader", { name: "ユーザーID" })).toBeInTheDocument();
    expect(screen.getByText("E-mail")).toBeInTheDocument();
    expect(screen.getByRole("button", { name: "検索" })).toBeInTheDocument();

    await waitFor(() => {
      expect(mocks.userIndex).toHaveBeenCalledWith({ page: "1" });
      expect(mocks.rolesIndex).toHaveBeenCalledWith();
    });
  });
});
