import {
    type RouteConfig,
    route,
  } from "@react-router/dev/routes";
  
  export default [
    route("some/path", "./routes/samepage.tsx"),
    // pattern ^           ^ module file
  ] satisfies RouteConfig;
