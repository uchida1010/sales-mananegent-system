import { customInstance } from "./custom-instance";
import type { AxiosResponse } from "axios";
import type { UserIndex200 } from "./salesManagementSystem";

export const fetchUsers = (
  params?: {
    keyword?: string;
    userCode?: string;
    email?: string;
    activeOnly?: boolean;
    role?: string;
  }
): Promise<AxiosResponse<UserIndex200>> => {
  return customInstance({
    url: "/user",
    method: "GET",
    params,
  });
};