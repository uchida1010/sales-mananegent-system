import "../../app.css";
import { DoubleNavbar } from "../../components/DoubleNavbar";
import { useUserCreate } from "./useUserCreate";
import { rolesIndex } from "~/api/salesManagementSystem";

export function meta() {
  return [{ title: "ユーザー登録ページ" }, { name: "description", content: "User Create Page" }];
}

export async function clientLoader() {
  const rolesResponse = await rolesIndex();

  return {
    roles: rolesResponse.data,
  };
}

export default function UserCreate({
  loaderData,
}: {
  loaderData: Awaited<ReturnType<typeof clientLoader>>;
}) {
  const { roles } = loaderData;

  const { storeForm, handleChange, error, errors, handleSubmit } = useUserCreate();

  return (
    <>
      <div className="flex md:flex-row bg-gray-100">
        <DoubleNavbar />
        <main className="flex-1 p-6">
          <h1 className="font-bold"> ユーザー登録</h1>
          <div className="flex gap-4 mt-2">
            <form
              className="flex w-full max-w-2xl flex-col gap-4 mt-2 rounded-lg border border-gray-200 bg-white p-6"
              onSubmit={(e) => {
                e.preventDefault();
                handleSubmit();
              }}
            >
              {error && (
                <div className="mt-2 rounded-md border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-600">
                  {error}
                </div>
              )}
              <div className="flex flex-col">
                <label className="mb-1 text-gray-600" htmlFor="user-code">
                  <div className="text-sm font-medium ">
                    ユーザーコード
                    <span className="ml-2 rounded bg-red-500 px-1.5 py-0.5 text-xs font-semibold text-white">
                      必須
                    </span>
                  </div>
                </label>
                <input
                  id="user-code"
                  type="text"
                  value={storeForm.userCode}
                  onChange={(e) => handleChange("userCode", e.target.value)}
                  className="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm
                            outline-none placeholder:text-gray-400
                            focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                />

                {errors.userCode?.[0] && (
                  <div className="mt-1 text-sm text-red-500">{errors.userCode[0]}</div>
                )}
              </div>

              <div className="flex flex-col">
                <label className="mb-1 text-gray-600" htmlFor="name">
                  <div className="text-sm font-medium ">
                    氏名
                    <span className="ml-2 rounded bg-red-500 px-1.5 py-0.5 text-xs font-semibold text-white">
                      必須
                    </span>
                  </div>
                </label>
                <input
                  id="name"
                  type="text"
                  value={storeForm.name}
                  onChange={(e) => handleChange("name", e.target.value)}
                  className="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm
                            outline-none placeholder:text-gray-400
                            focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                />

                {errors.name?.[0] && (
                  <div className="mt-1 text-sm text-red-500">{errors.name[0]}</div>
                )}
              </div>

              <div className="flex flex-col">
                <label className="mb-1 text-gray-600" htmlFor="name_kana">
                  <div className="text-sm font-medium ">よみがな</div>
                </label>
                <input
                  id="name_kana"
                  type="text"
                  value={storeForm.name_kana ?? ""}
                  onChange={(e) => handleChange("name_kana", e.target.value)}
                  className="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm
                            outline-none placeholder:text-gray-400
                            focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                />

                {errors.name_kana?.[0] && (
                  <div className="mt-1 text-sm text-red-500">{errors.name_kana[0]}</div>
                )}
              </div>

              <div className="flex flex-col">
                <label className="mb-1 text-gray-600" htmlFor="email">
                  <div className="text-sm font-medium ">
                    メールアドレス
                    <span className="ml-2 rounded bg-red-500 px-1.5 py-0.5 text-xs font-semibold text-white">
                      必須
                    </span>
                  </div>
                </label>
                <input
                  id="email"
                  type="email"
                  value={storeForm.email}
                  onChange={(e) => handleChange("email", e.target.value)}
                  className="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm
                            outline-none placeholder:text-gray-400
                            focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                />

                {errors.email?.[0] && (
                  <div className="mt-1 text-sm text-red-500">{errors.email[0]}</div>
                )}
              </div>

              <div className="flex flex-col">
                <label className="mb-1 text-gray-600" htmlFor="phone">
                  <div className="text-sm font-medium ">電話番号</div>
                </label>
                <input
                  id="phone"
                  type="tel"
                  value={storeForm.phone ?? ""}
                  onChange={(e) => handleChange("phone", e.target.value)}
                  className="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm
                            outline-none placeholder:text-gray-400
                            focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                />

                {errors.phone?.[0] && (
                  <div className="mt-1 text-sm text-red-500">{errors.phone[0]}</div>
                )}
              </div>

              <div className="flex flex-col">
                <label className="mb-1 text-gray-600" htmlFor="position">
                  <div className="text-sm font-medium ">役職</div>
                </label>
                <input
                  id="position"
                  type="text"
                  value={storeForm.position ?? ""}
                  onChange={(e) => handleChange("position", e.target.value)}
                  className="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm
                            outline-none placeholder:text-gray-400
                            focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                />

                {errors.position?.[0] && (
                  <div className="mt-1 text-sm text-red-500">{errors.position[0]}</div>
                )}
              </div>

              <div className="flex flex-col">
                <label className="mb-1 text-gray-600" htmlFor="roleId">
                  <div className="text-sm font-medium ">
                    権限
                    <span className="ml-2 rounded bg-red-500 px-1.5 py-0.5 text-xs font-semibold text-white">
                      必須
                    </span>
                  </div>
                </label>
                <select
                  id="roleId"
                  value={storeForm.roleId}
                  onChange={(e) => handleChange("roleId", e.target.value)}
                  className="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm
                            outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                >
                  <option value="">選択してください</option>

                  {roles.map((role) => (
                    <option key={role.id} value={role.id}>
                      {role.name}
                    </option>
                  ))}
                </select>

                {errors.roleId?.[0] && (
                  <div className="mt-1 text-sm text-red-500">{errors.roleId[0]}</div>
                )}
              </div>

              <div className="flex flex-col">
                <label className="mb-1 text-gray-600" htmlFor="joined_at">
                  <div className="text-sm font-medium ">
                    入社日
                    <span className="ml-2 rounded bg-red-500 px-1.5 py-0.5 text-xs font-semibold text-white">
                      必須
                    </span>
                  </div>
                </label>
                <input
                  id="joined_at"
                  type="date"
                  value={storeForm.joined_at}
                  onChange={(e) => handleChange("joined_at", e.target.value)}
                  className="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm
                            outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                />

                {errors.joined_at?.[0] && (
                  <div className="mt-1 text-sm text-red-500">{errors.joined_at[0]}</div>
                )}
              </div>

              <button
                type="submit"
                className="w-fit rounded-md bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white
                          shadow-sm transition
                          hover:bg-blue-700 hover:shadow-md
                          active:bg-blue-800 active:shadow-sm"
              >
                <div className="text-sm">登録</div>
              </button>
            </form>
          </div>
        </main>
      </div>
    </>
  );
}
