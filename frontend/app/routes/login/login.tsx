
export default function Login() {
  return (
    <div className="flex min-h-screen items-center justify-center bg-slate-100 px-4 py-8">
      <div className="w-full max-w-md">
        <div className="mb-8 flex items-center justify-center gap-3">
          <div className="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-800 text-white shadow-sm">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="1.8"
              strokeLinecap="round"
              strokeLinejoin="round"
              aria-hidden="true"
            >
              <path d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Z" />
              <path d="m4 7.5 8 4.5 8-4.5M12 12v9" />
            </svg>
          </div>

          <h1 className="text-xl font-bold tracking-tight text-slate-800">
            販売管理システム
          </h1>
        </div>

        <div className="rounded-2xl border border-slate-200 bg-white p-8 shadow-lg shadow-slate-200/60 sm:p-10">
          <div className="mb-8 text-center">
            <h2 className="text-2xl font-bold text-slate-800">
              ログイン
            </h2>
            <p className="mt-2 text-sm text-slate-500">
              アカウント情報を入力してください
            </p>
          </div>

          <form className="space-y-6">
            <div>
              <label
                htmlFor="email"
                className="mb-2 block text-sm font-semibold text-slate-700"
              >
                メールアドレス
              </label>

              <input
                id="email"
                type="email"
                name="email"
                placeholder="example@company.com"
                autoComplete="email"
                required
                className="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
              />
            </div>

            <div>
              <label
                htmlFor="password"
                className="mb-2 block text-sm font-semibold text-slate-700"
              >
                パスワード
              </label>

              <input
                id="password"
                type="password"
                name="password"
                placeholder="パスワードを入力"
                autoComplete="current-password"
                required
                className="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
              />
            </div>

            <div className="flex items-center gap-2">
              <input
                id="remember"
                type="checkbox"
                name="remember"
                defaultChecked
                className="h-4 w-4 cursor-pointer rounded border-slate-300 accent-blue-600"
              />

              <label
                htmlFor="remember"
                className="cursor-pointer text-sm text-slate-600"
              >
                ログイン状態を保持する
              </label>
            </div>

            <button
              type="button"
              className="w-full rounded-lg bg-slate-800 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-800"
            >
              ログイン
            </button>
          </form>
        </div>
        
        <p className="mt-8 text-center text-xs text-slate-400">
          © 2026 Sales Management System
        </p>
      </div>
    </div>
  );
}
