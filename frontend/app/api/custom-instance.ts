import Axios, { type AxiosError, type AxiosRequestConfig } from 'axios';

export const AXIOS_INSTANCE = Axios.create({
  baseURL: "http://localhost:8000/",
  withCredentials: true,
  withXSRFToken: true,
  headers: {
    Accept: "application/json",
  },
});

export const customInstance = <T>(config: AxiosRequestConfig): Promise<T> => {
  const authPaths = [
    "/login",
    "/logout",
    "/sanctum/csrf-cookie",
  ];

  const url = config.url ?? "";

  let requestUrl = url;

  if (!authPaths.includes(url)) {
    requestUrl = `/api${url}`;
  }

  const promise = AXIOS_INSTANCE({ ...config, url: requestUrl, }).then(({ data }) => data);

  return promise;
};

export type ErrorType<Error> = AxiosError<Error>;