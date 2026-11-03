import { type RouteConfig, route } from "@react-router/dev/routes";

export default [
  route("login", "./routes/login/login.tsx"),
  route("user", "./routes/user/index.tsx"),
  route("user/create", "./routes/user/create.tsx"),
  route("users/:userCode/edit", "./routes/user/edit.tsx"),
] satisfies RouteConfig;
