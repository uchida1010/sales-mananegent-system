import { useState, useEffect } from "react";
import { userIndex, type UserResource } from "../../api/salesManagementSystem";
import "../../app.css";
import { DoubleNavbar } from "../../components/DoubleNavbar";
import { useSearchParams } from "react-router";
import { Pagination } from "@mantine/core";
import { rolesIndex, type RoleResource } from "~/api/salesManagementSystem";

export function meta() {
  return [
    { title: "ユーザー一覧ページ" },
    { name: "description", content: "User Index Page" },
  ];
}

export default function UserIndex() {
  const [users, setUsers] = useState<UserResource[]>([]);
  const initialState = {
    keyword: "",
    userCode: "",
    email: "",
    activeOnly: false,
    roleId: "",
  };
  const [searchForm, setSearchForm] = useState(initialState);
  const [searchParams, setSearchParams] = useSearchParams();
  const getSearchParamsFromUrl = () => {
    return {
      keyword: searchParams.get("keyword") || "",
      userCode: searchParams.get("userCode") || "",
      email: searchParams.get("email") || "",
      roleId: searchParams.get("roleId") || "",
      activeOnly: searchParams.get("activeOnly") === "1",
      page: Number(searchParams.get("page") || "1"),
    };
  };

  const toApiParams = (
    params: ReturnType<typeof getSearchParamsFromUrl>,
  ): Parameters<typeof userIndex>[0] => ({
    ...(params.keyword && { keyword: params.keyword }),
    ...(params.userCode && { userCode: params.userCode }),
    ...(params.email && { email: params.email }),
    ...(params.roleId && { roleId: params.roleId }),
    ...(params.activeOnly && { activeOnly: "1" }),
    page: String(params.page),
  });
  const loadUsers = async (params?: Parameters<typeof userIndex>[0]) => {
    const res = await userIndex(params);

    setUsers(res.data);
    setTotalPages(res.meta.last_page);
  };
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [roles, setRoles] = useState<RoleResource[]>([]);

  useEffect(() => {
    const params = getSearchParamsFromUrl();

    setSearchForm(params);
    setPage(params.page);
    loadUsers(toApiParams(params));
    loadRoles();
  }, [searchParams]);

  const buildSearchParams = (page?: number) => ({
    ...(searchForm.keyword && { keyword: searchForm.keyword }),
    ...(searchForm.userCode && { userCode: searchForm.userCode }),
    ...(searchForm.email && { email: searchForm.email }),
    ...(searchForm.roleId && { roleId: searchForm.roleId }),
    ...(searchForm.activeOnly && { activeOnly: "1" }),
    ...(page && { page: String(page) }),
  });

  const handleSearch = () => {
    setPage(1);

    setSearchParams(buildSearchParams(1));
  };

  const handlePageChange = (newPage: number) => {
    setSearchParams(buildSearchParams(newPage));
  };

  const clearSearch = () => {
    setSearchParams({});
  };

  const loadRoles = async () => {
    const res = await rolesIndex();

    setRoles(res.data);
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
                    hover:bg-blue-600 active:bg-bluze-700 transition-colors"
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
                  value={searchForm.roleId}
                  onChange={(e) =>
                    setSearchForm({
                      ...searchForm,
                      roleId: e.target.value,
                    })
                  }
                  className="w-48 rounded-md border border-gray-300 bg-white px-3 py-1 text-sm
         focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                >
                  <option value="">すべて</option>
                  {roles.map((role) => (
                    <option key={role.id} value={role.id}>
                      {role.name}
                    </option>
                  ))}
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

          <div className="w-full rounded-lg border border-gray-50 bg-white mt-2 flex min-h-[560px] flex-col">
            <div className="flex-1">
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
                      <td className="px-4 py-3">{user.userCode}</td>
                      <td className="px-4 py-3">{user.name}</td>
                      <td className="px-4 py-3">{user.email}</td>
                      <td className="px-4 py-3">{user.roles}</td>
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
            <div className="mt-auto flex flex-col items-center gap-3 border-t border-gray-100 bg-white px-4 py-5">
              <Pagination
                total={totalPages}
                value={page}
                onChange={handlePageChange}
                siblings={1}
                boundaries={1}
                withEdges
              />

              <div className="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-sm text-gray-600 shadow-sm">
                <span className="font-medium text-gray-800">{page}</span>
                <span>ページ目</span>
                <span className="text-gray-300">/</span>
                <span>全</span>
                <span className="font-medium text-gray-800">{totalPages}</span>
                <span>ページ</span>
              </div>
            </div>
          </div>
        </main>
      </div>
    </>
  );
}
