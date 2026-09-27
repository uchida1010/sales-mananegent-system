import { type RouteConfig, route } from "@react-router/dev/routes";

export default [
  route("user", "./routes/user/index.tsx"),
  route("user/create", "./routes/user/create.tsx"),
  // pattern ^           ^ module file
] satisfies RouteConfig;
