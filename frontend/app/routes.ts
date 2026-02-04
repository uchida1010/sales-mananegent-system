import {
    type RouteConfig,
    route,
  } from "@react-router/dev/routes";
  
  export default [
    route("user", "./routes/user.tsx"),
    // pattern ^           ^ module file
  ] satisfies RouteConfig;
