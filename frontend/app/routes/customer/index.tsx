import "../../app.css";
import { DoubleNavbar } from "../../components/DoubleNavbar";

export function meta() {
  return [{ title: "取引先一覧ページ" }, { name: "description", content: "Customer Index Page" }];
}

export default function CustomerIndex() {
  return (
    <>
      <div className="flex min-h-screen bg-gray-100">
        <DoubleNavbar />
        <main className="flex min-w-0 flex-1 flex-col p-6">
          <h1 className="font-bold"> 取引先一覧</h1>
          <div className="flex gap-4 mt-2">
            <button
              className="bg-blue-500 text-white px-3 py-1 rounded-sm
                    hover:bg-blue-600 active:bg-blue-700 transition-colors"
            >
              <div className="text-sm">新規登録</div>
            </button>
            <input
              type="search"
              placeholder="取引先名、よみがなで検索"
              value=""
              className="w-52 rounded-sm border border-gray-300 bg-white px-3 py-1 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 placeholder:text-sm"
            />
          </div>

          <form
            className="rounded-lg border border-gray-200 bg-white p-4 mt-2"
            onSubmit={(e) => {
              e.preventDefault();
            }}
          >
            <div className="flex flex-wrap gap-4">
              <div className="flex flex-col">
                <label className="mb-1 text-gray-600" htmlFor="id">
                  <div className="text-sm font-medium ">取引先ID</div>
                </label>
                <input
                  id="id"
                  type="number"
                  value=""
                  className="w-20 rounded-md border border-gray-300 px-3 py-1 text-sm
             placeholder:text-sm placeholder:text-gray-400
             focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                />
              </div>

              <div className="flex flex-col">
                <label className="mb-1 text-sm font-medium text-gray-600" htmlFor="address">
                  <div className="text-sm font-medium">住所</div>
                </label>
                <input
                  id="address"
                  type="text"
                  value=""
                  className="w-64 rounded-md border border-gray-300 px-3 py-1 text-sm
                placeholder:text-sm placeholder:text-gray-400
                focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                />
              </div>
              <div className="flex flex-col">
                <label className="mb-1 text-sm font-medium text-gray-600" htmlFor="email">
                  <div className="text-sm font-medium">E-mail</div>
                </label>
                <input
                  id="email"
                  type="email"
                  placeholder="example@company.com"
                  value=""
                  className="w-64 rounded-md border border-gray-300 px-3 py-1 text-sm
             placeholder:text-sm placeholder:text-gray-400
             focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                />
              </div>
              <div className="flex flex-col">
                <label className="mb-1 text-sm font-medium text-gray-600" htmlFor="telFax">
                  <div className="text-sm font-medium">TEL/FAX</div>
                </label>
                <input
                  id="telFax"
                  type="text"
                  placeholder="123-456-789"
                  value=""
                  className="w-48 rounded-md border border-gray-300 px-3 py-1 text-sm
             placeholder:text-sm placeholder:text-gray-400
             focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                />
              </div>
              <div className="flex flex-col">
                <label className="mb-1 text-gray-600" htmlFor="person_in_charge_id">
                  <div className="text-sm font-medium">担当</div>
                </label>

                <select
                  id="person_in_charge_id"
                  value=""
                  className="w-48 rounded-md border border-gray-300 bg-white px-3 py-1 text-sm
         focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                >
                  <option value="">すべて</option>
                  <option key="" value="">
                    山田太郎
                  </option>
                  <option key="" value="">
                    田中花子
                  </option>
                </select>
              </div>
              <div className="flex flex-col">
                <label className="mb-1 text-gray-600" htmlFor="lead_source_id">
                  <div className="text-sm font-medium">リード獲得元</div>
                </label>

                <select
                  id="lead_source_id"
                  value=""
                  className="w-48 rounded-md border border-gray-300 bg-white px-3 py-1 text-sm
         focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                >
                  <option value="">すべて</option>
                  <option key="" value="">
                    HP
                  </option>
                  <option key="" value="">
                    営業
                  </option>
                  <option key="" value="">
                    紹介
                  </option>
                </select>
              </div>
            </div>
            <div className="pt-6">
              <button
                type="submit"
                className="rounded-sm bg-blue-500 px-5 py-1 font-semibold text-white
             hover:bg-blue-600 transition"
              >
                <div className="text-sm">検索</div>
              </button>
              <button
                type="button"
                className="rounded-sm border border-gray-300 px-5 py-1 text-sm text-gray-700 ml-2
         hover:bg-gray-100 transition"
              >
                <div className="text-sm">クリア</div>
              </button>
            </div>
          </form>

          <div className="mt-2 flex min-h-[560px] w-full flex-1 flex-col rounded-lg border border-gray-50 bg-white">
            <div className="flex-1">
              <table className="min-w-full text-left text-sm">
                <thead className="bg-gray-200 text-gray-600 border-b border-gray-200">
                  <tr className="divide-x divide-white">
                    <th className="w-32 px-4 py-3 font-semibold">取引先ID</th>
                    <th className="w-64 px-4 py-3 font-semibold">取引先名</th>
                    <th className="w-72 px-4 py-3 font-semibold">住所</th>
                    <th className="px-4 py-3 font-semibold">担当</th>
                    <th className="px-4 py-3 font-semibold">リード獲得元</th>
                    <th className="px-4 py-3 font-semibold">受注回数</th>
                    <th className="px-4 py-3 font-semibold">発注回数</th>
                  </tr>
                </thead>

                <tbody className="divide-x divide-gray-200">
                  <tr className="hover:bg-gray-50 divide-x divide-gray-200">
                    <td className="px-4 py-3 text-left">1</td>
                    <td className="px-4 py-3">ABC株式会社</td>
                    <td className="px-4 py-3">大阪府大阪市</td>
                    <td className="px-4 py-3">山田太郎</td>
                    <td className="px-4 py-3">HP</td>
                    <td className="px-4 py-3">0</td>
                    <td className="px-4 py-3">1</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div className="mt-auto flex flex-col items-center gap-3 border-t border-gray-100 bg-white px-4 py-5"></div>
          </div>
        </main>
      </div>
    </>
  );
}
