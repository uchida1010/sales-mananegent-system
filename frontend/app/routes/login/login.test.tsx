import { render, screen, waitFor } from "@testing-library/react";
import Login from "./login";
import { MemoryRouter } from "react-router";
import userEvent from "@testing-library/user-event";
import { sanctumCsrfCookie, loginStore, me } from "~/api/salesManagementSystem";
import { AxiosError, type AxiosResponse } from "axios";
vi.mock("~/api/salesManagementSystem", () => ({
  sanctumCsrfCookie: vi.fn(),
  loginStore: vi.fn(),
  me: vi.fn(),
}));

beforeEach(() => {
    vi.resetAllMocks();
});

const mockNavigate = vi.fn<
  (to: string, options?: { replace?: boolean }) => void
>();

vi.mock("react-router", async (importOriginal) => {
  const actual = await importOriginal<typeof import("react-router")>();

  return {
    ...actual,
    useNavigate: () => mockNavigate,
  };
});

test("メールアドレス入力欄が表示される", () => {
  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  expect(screen.getByLabelText("メールアドレス")).toBeInTheDocument();
});

test("パスワード入力欄が表示される", () => {
  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  expect(screen.getByLabelText("パスワード")).toBeInTheDocument();
  expect(screen.getByLabelText("パスワード")).toHaveAttribute("type", "password");
});

test("ログイン状態保持のチェックを外せる", async () => {
  const user = userEvent.setup();

  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  const checkbox = screen.getByLabelText("ログイン状態を保持する");

  expect(checkbox).toBeChecked();

  await user.click(checkbox);

  expect(checkbox).not.toBeChecked();

  await user.click(checkbox);

  expect(checkbox).toBeChecked();
});

test("ログインボタンが表示される", () => {
  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  expect(screen.getByRole("button", { name: "ログイン" })).toBeInTheDocument();
});

test("ログインボタンが有効であるか確認する", () => {
  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  expect(screen.getByRole("button", { name: "ログイン" })).toBeEnabled();
});

test("メールアドレスを入力できること", async () => {
  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  const user = userEvent.setup();

  const emailInput = screen.getByLabelText("メールアドレス");

  await user.type(emailInput, "test@example.com");

  expect(emailInput).toHaveValue("test@example.com");
});

test("パスワードを入力できること", async () => {
  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  const user = userEvent.setup();

  const passwordInput = screen.getByLabelText("パスワード");

  await user.type(passwordInput, "Password123!");

  expect(passwordInput).toHaveValue("Password123!");
});

test("ログインボタンをクリックするとログインAPIが呼び出されること", async () => {
  vi.mocked(sanctumCsrfCookie).mockResolvedValue(undefined);
  vi.mocked(loginStore).mockResolvedValue({
    two_factor: false,
  });

  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  const user = userEvent.setup();

  const loginButton = screen.getByRole("button", {
    name: "ログイン",
  });

  const emailInput = screen.getByLabelText("メールアドレス");
  const passwordInput = screen.getByLabelText("パスワード");

  await user.type(emailInput, "test@example.com");
  await user.type(passwordInput, "Password123!");

  await user.click(loginButton);

  expect(loginStore).toHaveBeenCalledWith({
    email: "test@example.com",
    password: "Password123!",
    remember: true,
  });

  expect(vi.mocked(sanctumCsrfCookie).mock.invocationCallOrder[0]).toBeLessThan(
    vi.mocked(loginStore).mock.invocationCallOrder[0],
  );
});

test("ログイン失敗時にエラーメッセージが表示されること", async () => {
  vi.mocked(sanctumCsrfCookie).mockResolvedValue(undefined);

  const response = {
    data: {
      message: "認証に失敗しました",
    },
    status: 422,
    statusText: "Unprocessable Content",
    headers: {},
    config: {
      headers: {},
    },
  } as AxiosResponse;

  const error = new AxiosError("認証失敗", "ERR_BAD_REQUEST", undefined, undefined, response);

  vi.mocked(loginStore).mockRejectedValue(error);

  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  const user = userEvent.setup();

  const emailInput = screen.getByLabelText("メールアドレス");

  await user.type(emailInput, "test@example.com");

  const passwordInput = screen.getByLabelText("パスワード");

  await user.type(passwordInput, "Password123!");

  const loginButton = screen.getByRole("button", {
    name: "ログイン",
  });

  await user.click(loginButton);

  const errorMessage = await screen.findByText("メールアドレスまたはパスワードが正しくありません");

  expect(errorMessage).toBeInTheDocument();

  expect(loginButton).toBeEnabled();
});

test("CSRF取得に失敗した場合はログインAPIを呼び出さないこと", async () => {
  vi.mocked(sanctumCsrfCookie).mockRejectedValue(new Error("CSRF取得失敗"));

  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  const user = userEvent.setup();

  const emailInput = screen.getByLabelText("メールアドレス");
  await user.type(emailInput, "test@example.com");

  const passwordInput = screen.getByLabelText("パスワード");
  await user.type(passwordInput, "Password123!");

  const loginButton = screen.getByRole("button", {
    name: "ログイン",
  });

  await user.click(loginButton);

  const errorMessage = await screen.findByText("ログイン処理に失敗しました");

  expect(errorMessage).toBeInTheDocument();

  expect(loginStore).not.toHaveBeenCalled();
});

test("ログイン処理中はボタンが無効になること", async () => {
  vi.mocked(sanctumCsrfCookie).mockImplementation(() => new Promise(() => {}));

  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  const user = userEvent.setup();

  const emailInput = screen.getByLabelText("メールアドレス");
  await user.type(emailInput, "test@example.com");

  const passwordInput = screen.getByLabelText("パスワード");
  await user.type(passwordInput, "Password123!");

  const loginButton = screen.getByRole("button", {
    name: "ログイン",
  });

  await user.click(loginButton);

  expect(loginButton).toBeDisabled();
});

test("ログイン処理中に二重送信されないこと", async () => {
  vi.mocked(sanctumCsrfCookie).mockImplementation(() => new Promise(() => {}));

  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  const user = userEvent.setup();

  const emailInput = screen.getByLabelText("メールアドレス");
  await user.type(emailInput, "test@example.com");

  const passwordInput = screen.getByLabelText("パスワード");
  await user.type(passwordInput, "Password123!");

  const loginButton = screen.getByRole("button", {
    name: "ログイン",
  });

  await user.click(loginButton);

  await user.click(loginButton);

  expect(sanctumCsrfCookie).toHaveBeenCalledTimes(1);
});

test("ログイン失敗後に再試行できること", async () => {
  vi.mocked(sanctumCsrfCookie).mockResolvedValue(undefined);
  vi.mocked(loginStore).mockRejectedValue(new Error("ログイン失敗"));

  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  const user = userEvent.setup();

  await user.type(screen.getByLabelText("メールアドレス"), "test@example.com");

  await user.type(screen.getByLabelText("パスワード"), "Password123!");

  const loginButton = screen.getByRole("button", {
    name: "ログイン",
  });

  await user.click(loginButton);

  await screen.findByText("ログイン処理に失敗しました");

  expect(loginButton).toBeEnabled();

  await user.click(loginButton);

  expect(loginStore).toHaveBeenCalledTimes(2);
});

test("ログイン成功後にユーザー一覧へ遷移すること", async () => {
  vi.mocked(sanctumCsrfCookie).mockResolvedValue(undefined);
  vi.mocked(loginStore).mockResolvedValue({
    two_factor: false,
  });

  const error401 = new AxiosError("未認証", "ERR_BAD_REQUEST", undefined, undefined, {
    data: {},
    status: 401,
    statusText: "Unauthorized",
    headers: {},
    config: { headers: {} },
  } as AxiosResponse);

  vi.mocked(me).mockRejectedValueOnce(error401);

  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  const user = userEvent.setup();

  await user.type(screen.getByLabelText("メールアドレス"), "test@example.com");

  await user.type(screen.getByLabelText("パスワード"), "Password123!");

  const loginButton = screen.getByRole("button", {
    name: "ログイン",
  });

  await user.click(loginButton);

  await waitFor(() => {
    expect(mockNavigate).toHaveBeenCalledWith("/user");
  });

  expect(me).toHaveBeenCalledTimes(2);

  expect(vi.mocked(loginStore).mock.invocationCallOrder[0]).toBeLessThan(
    vi.mocked(me).mock.invocationCallOrder[1],
  );
});

test("セッション期限切れ時にエラーメッセージが表示されること", async () => {
  vi.mocked(sanctumCsrfCookie).mockResolvedValue(undefined);

  const response = {
    data: { message: "CSRF token mismatch." },
    status: 419,
    statusText: "Page Expired",
    headers: {},
    config: { headers: {} },
  } as AxiosResponse;

  const error = new AxiosError(
    "セッション期限切れ",
    "ERR_BAD_REQUEST",
    undefined,
    undefined,
    response,
  );

  vi.mocked(loginStore).mockRejectedValue(error);

  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  const user = userEvent.setup();

  await user.type(screen.getByLabelText("メールアドレス"), "test@example.com");

  await user.type(screen.getByLabelText("パスワード"), "Password123!");

  const loginButton = screen.getByRole("button", {
    name: "ログイン",
  });

  await user.click(loginButton);

  const errorMessage = await screen.findByText(
    "セッションの有効期限が切れました。もう一度ログインしてください",
  );

  expect(errorMessage).toBeInTheDocument();
});

test("ログイン試行回数制限時にエラーメッセージが表示されること", async () => {
  vi.mocked(sanctumCsrfCookie).mockResolvedValue(undefined);

  const response = {
    data: { message: "Too Many Requests" },
    status: 429,
    statusText: "Too Many Requests",
    headers: {},
    config: { headers: {} },
  } as AxiosResponse;

  const error = new AxiosError(
    "ログイン試行回数制限",
    "ERR_BAD_REQUEST",
    undefined,
    undefined,
    response,
  );

  vi.mocked(loginStore).mockRejectedValue(error);

  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  const user = userEvent.setup();

  const loginButton = screen.getByRole("button", {
    name: "ログイン",
  });

  const emailInput = screen.getByLabelText("メールアドレス");
  const passwordInput = screen.getByLabelText("パスワード");

  await user.type(emailInput, "test@example.com");
  await user.type(passwordInput, "Password123!");

  await user.click(loginButton);

  const errorMessage = await screen.findByText(
    "ログイン試行回数が上限に達しました。しばらく時間をおいてから再度お試しください",
  );

  expect(errorMessage).toBeInTheDocument();
});

test("ネットワークエラー時にメッセージが表示されること", async () => {
  vi.mocked(sanctumCsrfCookie).mockResolvedValue(undefined);

  const error = new AxiosError("Network Error", "ERR_NETWORK");

  vi.mocked(loginStore).mockRejectedValue(error);

  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  const user = userEvent.setup();

  const loginButton = screen.getByRole("button", {
    name: "ログイン",
  });

  const emailInput = screen.getByLabelText("メールアドレス");
  const passwordInput = screen.getByLabelText("パスワード");

  await user.type(emailInput, "test@example.com");
  await user.type(passwordInput, "Password123!");

  await user.click(loginButton);

  const errorMessage = await screen.findByText(
    "サーバーに接続できません。通信環境を確認してから再度お試しください。",
  );

  expect(errorMessage).toBeInTheDocument();
});

test("ログイン状態保持をオフにするとrememberがfalseで送信されること", async () => {
  vi.mocked(sanctumCsrfCookie).mockResolvedValue(undefined);
  vi.mocked(loginStore).mockResolvedValue({
    two_factor: false,
  });

  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  const user = userEvent.setup();

  const loginButton = screen.getByRole("button", {
    name: "ログイン",
  });

  await user.type(screen.getByLabelText("メールアドレス"), "test@example.com");

  await user.type(screen.getByLabelText("パスワード"), "Password123!");

  const checkbox = screen.getByLabelText("ログイン状態を保持する");

  expect(checkbox).toBeChecked();

  await user.click(checkbox);

  expect(checkbox).not.toBeChecked();

  await user.click(loginButton);

  expect(loginStore).toHaveBeenCalledWith({
    email: "test@example.com",
    password: "Password123!",
    remember: false,
  });
});

test("ログイン後の認証確認に失敗した場合は画面遷移しないこと", async () => {
  vi.mocked(sanctumCsrfCookie).mockResolvedValue(undefined);

  vi.mocked(loginStore).mockResolvedValue({
    two_factor: false,
  });

  const error401 = new AxiosError("未認証", "ERR_BAD_REQUEST", undefined, undefined, {
    data: {},
    status: 401,
    statusText: "Unauthorized",
    headers: {},
    config: { headers: {} },
  } as AxiosResponse);

  vi.mocked(me).mockRejectedValue(error401);

  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  const user = userEvent.setup();

  await user.type(screen.getByLabelText("メールアドレス"), "test@example.com");

  await user.type(screen.getByLabelText("パスワード"), "Password123!");

  await user.click(screen.getByRole("button", { name: "ログイン" }));

  expect(me).toHaveBeenCalledTimes(2);

  expect(mockNavigate).not.toHaveBeenCalled();
});

test("ログイン済みの場合はユーザー一覧へ自動遷移すること", async () => {
  vi.mocked(me).mockResolvedValue(undefined as never);

  render(
    <MemoryRouter>
      <Login />
    </MemoryRouter>,
  );

  await waitFor(() => {
    expect(mockNavigate).toHaveBeenCalledWith("/user");

    expect(loginStore).not.toHaveBeenCalled();
  });
});
