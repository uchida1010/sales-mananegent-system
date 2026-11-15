import { renderHook, act, screen } from "@testing-library/react";
import { useLogout } from "./useLogout";
import { logout } from "~/api/salesManagementSystem";
import { AxiosError, type AxiosResponse } from "axios";

const mockNavigate = vi.fn<(to: string, options?: { replace?: boolean }) => void>();

vi.mock("~/api/salesManagementSystem", () => ({
  logout: vi.fn(),
}));

vi.mock("react-router", async (importOriginal) => {
  const actual = await importOriginal<typeof import("react-router")>();

  return {
    ...actual,
    useNavigate: () => mockNavigate,
  };
});

beforeEach(() => {
  vi.resetAllMocks();
});

test("ログアウト成功時にログイン画面へ遷移すること", async () => {
  vi.mocked(logout).mockResolvedValue(undefined);

  const { result } = renderHook(() => useLogout());

  await act(async () => {
    await result.current.handleLogout();
  });

  expect(logout).toHaveBeenCalledTimes(1);

  expect(mockNavigate).toHaveBeenCalledWith("/login");
});

test("ログアウトAPIが401エラーの場合もログイン画面へ遷移すること", async () => {
  const error401 = new AxiosError("未認証", "ERR_BAD_REQUEST", undefined, undefined, {
    data: {},
    status: 401,
    statusText: "Unauthorized",
    headers: {},
    config: { headers: {} },
  } as AxiosResponse);

  vi.mocked(logout).mockRejectedValue(error401);

  const { result } = renderHook(() => useLogout());

  await act(async () => {
    await result.current.handleLogout();
  });

  expect(mockNavigate).toHaveBeenCalledWith("/login");
});

test("ログアウトAPIが403エラーの場合はエラーを表示して画面遷移しないこと", async () => {
  const error403 = new AxiosError("アクセス禁止", "ERR_BAD_REQUEST", undefined, undefined, {
    data: {},
    status: 403,
    statusText: "Forbidden",
    headers: {},
    config: { headers: {} },
  } as AxiosResponse);

  vi.mocked(logout).mockRejectedValue(error403);

  const { result } = renderHook(() => useLogout());

  await act(async () => {
    await result.current.handleLogout();
  });

  expect(mockNavigate).not.toHaveBeenCalled();

  expect(result.current.error).toBe("ログアウトに失敗しました");
});

test("ログアウトAPIが500エラーの場合は画面遷移しないこと", async () => {
  const error500 = new AxiosError("サーバーエラー", "ERR_BAD_RESPONSE", undefined, undefined, {
    data: {},
    status: 500,
    statusText: "Internal Server Error",
    headers: {},
    config: { headers: {} },
  } as AxiosResponse);

  vi.mocked(logout).mockRejectedValue(error500);

  const { result } = renderHook(() => useLogout());

  await act(async () => {
    await result.current.handleLogout();
  });

  expect(mockNavigate).not.toHaveBeenCalled();

  expect(result.current.error).toBe("ログアウトに失敗しました");
});

test("通信エラー時にエラーメッセージを設定すること", async () => {
  const error = new AxiosError("Network Error", "ERR_NETWORK");

  vi.mocked(logout).mockRejectedValue(error);

  const { result } = renderHook(() => useLogout());

  await act(async () => {
    await result.current.handleLogout();
  });

  expect(mockNavigate).not.toHaveBeenCalled();
  expect(result.current.error).toBe("ログアウトに失敗しました");
});

test("通信エラー後に再試行できる状態に戻ること", async () => {
  const error = new AxiosError("Network Error", "ERR_NETWORK");

  vi.mocked(logout).mockRejectedValue(error);

  const { result } = renderHook(() => useLogout());

  await act(async () => {
    await result.current.handleLogout();
  });

  expect(result.current.isSubmitting).toBe(false);
});

test("通信エラー後にログアウトを再試行できること", async () => {
  const error = new AxiosError("Network Error", "ERR_NETWORK");

  vi.mocked(logout).mockRejectedValueOnce(error).mockResolvedValueOnce(undefined);

  const { result } = renderHook(() => useLogout());

  await act(async () => {
    await result.current.handleLogout();
  });

  await act(async () => {
    await result.current.handleLogout();
  });

  expect(logout).toHaveBeenCalledTimes(2);

  expect(mockNavigate).toHaveBeenCalledWith("/login");
});

test("ログアウト処理中に二重送信されないこと", async () => {
  vi.mocked(logout).mockImplementation(() => new Promise(() => {}));

  const { result } = renderHook(() => useLogout());

  await act(async () => {
    void result.current.handleLogout();
    void result.current.handleLogout();
  });

  expect(logout).toHaveBeenCalledTimes(1);
});
