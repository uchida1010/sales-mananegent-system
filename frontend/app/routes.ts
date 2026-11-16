import { type RouteConfig, route, layout, index } from "@react-router/dev/routes";

export default [
  index("./routes/home.tsx"),

  route("login", "./routes/login/login.tsx"),

  layout("./components/ProtectedRoute.tsx", [
    route("user", "./routes/user/index.tsx"),
    route("user/create", "./routes/user/create.tsx"),
    route("users/:userCode/edit", "./routes/user/edit.tsx"),
  ]),
] satisfies RouteConfig;
