import { useState } from "react";
import { useSearchParams } from "react-router";

import {
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

export const parseUserSearchParams = (searchParams: URLSearchParams): UserSearchParams => ({
  keyword: searchParams.get("keyword") || "",
  userCode: searchParams.get("userCode") || "",
  email: searchParams.get("email") || "",
  roleId: searchParams.get("roleId") || "",
  activeOnly: searchParams.get("activeOnly") === "1",
  page: Number(searchParams.get("page") || "1"),
});

export const toSearchForm = (params: UserSearchParams): UserSearchForm => ({
  keyword: params.keyword,
  userCode: params.userCode,
  email: params.email,
  roleId: params.roleId,
  activeOnly: params.activeOnly,
});

export const buildUserIndexParams = (params: UserSearchParams): UserIndexParams => ({
  ...(params.keyword && { keyword: params.keyword }),
  ...(params.userCode && { userCode: params.userCode }),
  ...(params.email && { email: params.email }),
  ...(params.roleId && { roleId: params.roleId }),
  ...(params.activeOnly && { activeOnly: "1" }),
  page: String(params.page),
});

export const buildSearchParams = (searchForm: UserSearchForm, page: number) => ({
  ...(searchForm.keyword && { keyword: searchForm.keyword }),
  ...(searchForm.userCode && { userCode: searchForm.userCode }),
  ...(searchForm.email && { email: searchForm.email }),
  ...(searchForm.roleId && { roleId: searchForm.roleId }),
  ...(searchForm.activeOnly && { activeOnly: "1" }),
  page: String(page),
});

export type UserSearchData = {
  users: UserResource[];
  roles: RoleResource[];
  page: number;
  totalPages: number;
  searchForm: UserSearchForm;
};

export const useUserSearch = ({
  users,
  roles,
  page,
  totalPages,
  searchForm: initialForm,
}: UserSearchData) => {
  const [searchForm, setSearchForm] = useState<UserSearchForm>(initialForm);
  const [, setSearchParams] = useSearchParams();

  const handleSearch = () => {
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
