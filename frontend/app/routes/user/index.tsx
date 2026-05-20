import { useState, useEffect } from "react";
import { userIndex, type UserIndex200 } from "../../api/salesManagementSystem";
import "../../app.css";
import { DoubleNavbar } from "../../components/DoubleNavbar";
import { fetchUsers } from "../../api/userQuery";

export function meta({}: Route.MetaArgs) {
  return [
    { title: "ユーザー一覧ページ" },
    { name: "description", content: "User Index Page" },
  ];
}

export default function UserIndex() {
  const [users, setUsers] = useState<UserIndex200[]>([]);
  const initialState = {
    keyword: "",
    userCode: "",
    email: "",
    activeOnly: false,
    role: "",
  };
  const [searchForm, setSearchForm] = useState({ initialState });

  const loadUsers = async (params) => {
    const res = await fetchUsers(params);
    setUsers(res.data);
  };

  useEffect(() => {
    loadUsers();
  }, []);

  const handleSearch = async () => {
    loadUsers(searchForm);
  };

  const clearSearch = async () => {
    {
      setSearchForm(initialState);
      loadUsers(initialState);
    }
  };

  return (
    <>
      <div className="flex md:flex-row bg-gray-100">
        <DoubleNavbar />
        <main className="flex-1 p-6">
          <h1 className="font-bold"> ユーザー一覧</h1>
          <div className="flex gap-4 mt-2">
            <button
              className="bg-blue-500 text-white px-3 py-1 rounded-sm
                    hover:bg-blue-600 active:bg-blue-700 transition-colors"
            >
              <div className="text-sm">新規登録</div>
            </button>
            <input
              type="search"
              placeholder="名前、よみがなで検索"
              value={searchForm.keyword}
              onChange={(e) =>
                setSearchForm({
                  ...searchForm,
                  keyword: e.target.value,
                })
              }
              onKeyDown={(e) => {
                if (e.key === "Enter") {
                  handleSearch();
                }
              }}
              className="rounded-sm border border-gray-300 bg-white px-3 py-1 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 placeholder:text-sm"
            />

            <button className="rounded-sm border border-gray-300 px-3 py-1 text-gray-700 hover:bg-gray-200 transition">
              <div className="text-sm flex">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  className="h-5 w-5"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  strokeWidth="2"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    d="M6 15l6-6 6 6"
                  />
                </svg>
                詳細を閉じる
              </div>
            </button>

            <button className="rounded-sm border border-gray-300 px-3 py-1 text-gray-700 hover:bg-gray-200 transition">
              <div className="text-sm flex">職種追加</div>
            </button>

            <button className="rounded-sm border border-gray-300 px-3 py-1 text-gray-700 hover:bg-gray-200 transition">
              <div className="text-sm flex">権限追加</div>
            </button>
          </div>

          <form
            className="rounded-lg border border-gray-200 bg-white p-4 mt-2"
            onSubmit={(e) => {
              e.preventDefault();
              handleSearch();
            }}
          >
            <div className="flex flex-wrap gap-4">
              <div className="flex flex-col">
                <label className="mb-1 text-gray-600">
                  <div className="text-sm font-medium ">ユーザーID</div>
                </label>
                <input
                  type="text"
                  placeholder="0"
                  value={searchForm.userCode}
                  onChange={(e) =>
                    setSearchForm({
                      ...searchForm,
                      userCode: e.target.value,
                    })
                  }
                  className="w-48 rounded-md border border-gray-300 px-3 py-1 text-sm
             placeholder:text-sm placeholder:text-gray-400
             focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                />
              </div>

              <div className="flex flex-col">
                <label className="mb-1 text-sm font-medium text-gray-600">
                  <div className="text-sm font-medium ">E-mail</div>
                </label>
                <input
                  type="email"
                  placeholder="example@company.com"
                  value={searchForm.email}
                  onChange={(e) =>
                    setSearchForm({
                      ...searchForm,
                      email: e.target.value,
                    })
                  }
                  className="w-64 rounded-md border border-gray-300 px-3 py-1 text-sm
             placeholder:text-sm placeholder:text-gray-400
             focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                />
              </div>

              <div className="flex flex-col">
                <label className="mb-1 text-gray-600">
                  <div className="text-sm font-medium">権限</div>
                </label>

                <select
                  value={searchForm.role}
                  onChange={(e) =>
                    setSearchForm({
                      ...searchForm,
                      role: e.target.value,
                    })
                  }
                  className="w-48 rounded-md border border-gray-300 bg-white px-3 py-1 text-sm
      focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                >
                  <option value="">すべて</option>
                  <option value="admin">システム管理者</option>
                  <option value="office_worker">事務員</option>
                  <option value="sales">営業</option>
                </select>
              </div>

              <div className="flex gap-2 pt-6">
                <input
                  id="active"
                  type="checkbox"
                  checked={searchForm.activeOnly}
                  onChange={(e) =>
                    setSearchForm({
                      ...searchForm,
                      activeOnly: e.target.checked,
                    })
                  }
                  className="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-400"
                />
                <label className="text-gray-700">
                  <div className="text-sm font-medium ">在職者のみ</div>
                </label>
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
                onClick={clearSearch}
              >
                <div className="text-sm">クリア</div>
              </button>
            </div>
          </form>

          <div className="w-full rounded-lg border border-gray-50 bg-white mt-2">
            <table className="min-w-full text-left text-sm">
              <thead className="bg-gray-200 text-gray-600 border-b border-gray-200">
                <tr className="divide-x divide-white">
                  <th className="px-4 py-3 font-semibold">ユーザーID</th>
                  <th className="px-4 py-3 font-semibold">氏名</th>
                  <th className="px-4 py-3 font-semibold">メール</th>
                  <th className="px-4 py-3 font-semibold">権限</th>
                  <th className="px-4 py-3 font-semibold">状態</th>
                </tr>
              </thead>

              <tbody className="divide-x divide-gray-200">
                {users.map((user) => (
                  <tr
                    key={user.id}
                    className="hover:bg-gray-50 divide-x divide-gray-200"
                  >
                    <td className="px-4 py-3">{user.user_code}</td>
                    <td className="px-4 py-3">{user.name}</td>
                    <td className="px-4 py-3">{user.email}</td>
                    <td className="px-4 py-3">{user.roles?.join(", ")}</td>
                    <td className="px-4 py-3">
                      {user.status === "active" ? (
                        <span className="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">
                          在職
                        </span>
                      ) : user.status === "leave" ? (
                        <span className="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">
                          退職
                        </span>
                      ) : (
                        <span className="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">
                          休職
                        </span>
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </>
  );
}
