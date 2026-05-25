import { customInstance } from "./custom-instance";
import type { UserIndex200 } from "./salesManagementSystem";

export const fetchUsers = (
  params?: {
    keyword?: string;
    userCode?: string;
    email?: string;
    activeOnly?: boolean;
    role?: string;
  }
): Promise<UserIndex200> => {
  return customInstance({
    url: "/user",
    method: "GET",
    params,
  });
};