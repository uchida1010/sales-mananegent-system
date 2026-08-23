import { useEffect, useState } from "react";
import { useSearchParams } from "react-router";

import {
  rolesIndex,
  userIndex,
  type RoleResource,
  type UserIndexParams,
  type UserResource,
} from "~/api/salesManagementSystem";

export type UserSearchForm = {
  keyword: string;
  userCode: string;
  email: string;
  activeOnly: boolean;
  roleId: string;
};

export type UserSearchParams = UserSearchForm & {
  page: number;
};

const initialSearchForm: UserSearchForm = {
  keyword: "",
  userCode: "",
  email: "",
  activeOnly: false,
  roleId: "",
};

const parseUserSearchParams = (searchParams: URLSearchParams): UserSearchParams => ({
  keyword: searchParams.get("keyword") || "",
  userCode: searchParams.get("userCode") || "",
  email: searchParams.get("email") || "",
  roleId: searchParams.get("roleId") || "",
  activeOnly: searchParams.get("activeOnly") === "1",
  page: Number(searchParams.get("page") || "1"),
});

const toSearchForm = (params: UserSearchParams): UserSearchForm => ({
  keyword: params.keyword,
  userCode: params.userCode,
  email: params.email,
  roleId: params.roleId,
  activeOnly: params.activeOnly,
});

const buildUserIndexParams = (params: UserSearchParams): UserIndexParams => ({
  ...(params.keyword && { keyword: params.keyword }),
  ...(params.userCode && { userCode: params.userCode }),
  ...(params.email && { email: params.email }),
  ...(params.roleId && { roleId: params.roleId }),
  ...(params.activeOnly && { activeOnly: "1" }),
  page: String(params.page),
});

const buildSearchParams = (searchForm: UserSearchForm, page: number) => ({
  ...(searchForm.keyword && { keyword: searchForm.keyword }),
  ...(searchForm.userCode && { userCode: searchForm.userCode }),
  ...(searchForm.email && { email: searchForm.email }),
  ...(searchForm.roleId && { roleId: searchForm.roleId }),
  ...(searchForm.activeOnly && { activeOnly: "1" }),
  page: String(page),
});

export const useUserSearch = () => {
  const [users, setUsers] = useState<UserResource[]>([]);
  const [searchForm, setSearchForm] = useState<UserSearchForm>(initialSearchForm);
  const [searchParams, setSearchParams] = useSearchParams();
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [roles, setRoles] = useState<RoleResource[]>([]);

  useEffect(() => {
    const loadUsers = async (params: UserIndexParams) => {
      const res = await userIndex(params);

      setUsers(res.data);
      setTotalPages(res.meta.last_page);
    };
    const loadRoles = async () => {
      const res = await rolesIndex();

      setRoles(res.data);
    };

    const params = parseUserSearchParams(searchParams);

    setSearchForm(toSearchForm(params));
    setPage(params.page);
    loadUsers(buildUserIndexParams(params));
    loadRoles();
  }, [searchParams]);

  const handleSearch = () => {
    setPage(1);
    setSearchParams(buildSearchParams(searchForm, 1));
  };

  const handlePageChange = (newPage: number) => {
    setSearchParams(buildSearchParams(searchForm, newPage));
  };

  const clearSearch = () => {
    setSearchParams({});
  };

  return {
    users,
    searchForm,
    setSearchForm,
    page,
    totalPages,
    roles,
    handleSearch,
    handlePageChange,
    clearSearch,
  };
};
