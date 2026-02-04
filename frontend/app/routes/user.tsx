import type { Route } from "./+types/user";
import "../app.css";
import { DoubleNavbar } from "../components/DoubleNavbar";

export function meta({}: Route.MetaArgs) {
  return [
    { title: "Sample Page" },
    { name: "description", content: "Sample Page" },
  ];
}

export default function SamplePage() {
  return (
    <>
      <div class="flex md:flex-row bg-gray-100">
        <DoubleNavbar />
        <main class="flex-1 p-6">
          <h1 class="font-bold"> ユーザー一覧</h1>
          <div class="flex gap-4 mt-2">
            <button
              class="bg-blue-500 text-white px-3 py-1 rounded-sm
                    hover:bg-blue-600 active:bg-blue-700 transition-colors"
            >
              <div class="text-sm">新規登録</div>
            </button>
            <input
              type="search"
              placeholder="名前、よみがなで検索"
              class="rounded-sm border border-gray-300 bg-white px-3 py-1
         text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 placeholder:text-sm"
            />

            <button
              class="rounded-sm border border-gray-300 px-3 py-1 text-gray-700
         hover:bg-gray-100 transition"
            >
              <div class="text-sm flex">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  stroke-width="2"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6 15l6-6 6 6"
                  />
                </svg>
                詳細を閉じる
              </div>
            </button>
          </div>

          <form class="rounded-lg border border-gray-200 bg-white p-4 mt-2">
            <div class="flex flex-wrap gap-4">
              <div class="flex flex-col">
                <label class="mb-1 text-gray-600">
                  <div class="text-sm font-medium ">ユーザーID</div>
                </label>
                <input
                  type="text"
                  placeholder="U-001"
                  class="w-48 rounded-md border border-gray-300 px-3 py-1 text-sm
             placeholder:text-sm placeholder:text-gray-400
             focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                />
              </div>

              <div class="flex flex-col">
                <label class="mb-1 text-sm font-medium text-gray-600">
                  <div class="text-sm font-medium ">E-mail</div>
                </label>
                <input
                  type="email"
                  placeholder="example@company.com"
                  class="w-64 rounded-md border border-gray-300 px-3 py-1 text-sm
             placeholder:text-sm placeholder:text-gray-400
             focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                />
              </div>

              <div class="flex gap-2 pt-6">
                <input
                  id="active"
                  type="checkbox"
                  class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-400"
                />
                <label for="active" class="text-gray-700">
                  <div class="text-sm font-medium ">在職者のみ</div>
                </label>
              </div>
            </div>
            <div class="pt-6">
              <button
                type="submit"
                class="rounded-sm bg-blue-500 px-5 py-1 font-semibold text-white
             hover:bg-blue-600 transition"
              >
                <div class="text-sm">検索</div>
              </button>
              <button
                type="button"
                class="rounded-sm border border-gray-300 px-5 py-1 text-sm text-gray-700 ml-2
         hover:bg-gray-100 transition"
              >
                <div class="text-sm">クリア</div>
              </button>
            </div>
          </form>

          <div class="w-full rounded-lg border border-gray-50 bg-white mt-2">
            <table class="min-w-full text-left text-sm">
              <thead class="bg-gray-200 text-gray-600 border-b border-gray-200">
                <tr class="divide-x divide-white">
                  <th class="px-4 py-3 font-semibold">ユーザーID</th>
                  <th class="px-4 py-3 font-semibold">氏名</th>
                  <th class="px-4 py-3 font-semibold">メール</th>
                  <th class="px-4 py-3 font-semibold">権限</th>
                  <th class="px-4 py-3 font-semibold">状態</th>
                </tr>
              </thead>

              <tbody class="divide-x divide-gray-200">
                <tr class="hover:bg-gray-50 divide-x divide-gray-200">
                  <td class="px-4 py-3">U-001</td>
                  <td class="px-4 py-3">山田 太郎</td>
                  <td class="px-4 py-3">taro@example.com</td>
                  <td class="px-4 py-3">管理者</td>
                  <td class="px-4 py-3">
                    <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">
                      有効
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </>
  );
}
