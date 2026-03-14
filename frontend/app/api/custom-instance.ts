import Axios, { type AxiosError, type AxiosRequestConfig } from 'axios';

export const AXIOS_INSTANCE = Axios.create({ baseURL: "http://localhost:8000/api" });

export const customInstance = <T>(config: AxiosRequestConfig): Promise<T> => {
  const promise = AXIOS_INSTANCE({ ...config }).then(({ data }) => data);

  return promise;
};

export type ErrorType<Error> = AxiosError<Error>;