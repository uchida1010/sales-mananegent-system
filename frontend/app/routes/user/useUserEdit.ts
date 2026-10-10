import { useState, useEffect } from "react";
import { userShow, userUpdate, type UserUpdateRequest } from "~/api/salesManagementSystem";
import { useNavigate } from "react-router";
import Axios from "axios";

export type ValidationErrors = {
  userCode?: string[];
  name?: string[];
  name_kana?: string[];
  email?: string[];
  password?: string[];
  password_confirmation?: string[];
  phone?: string[];
  position?: string[];
  joined_at?: string[];
  resigned_at?: string[];
  roleId?: string[];
};

export type ValidationErrorResponse = {
  message: string;
  errors: ValidationErrors;
};

export const useUserEdit = (userCode: string | undefined) => {
  const [error, setError] = useState("");

  const [errors, setErrors] = useState<ValidationErrors>({
    userCode: [],
    name: [],
    name_kana: [],
    email: [],
    password: [],
    password_confirmation: [],
    phone: [],
    position: [],
    joined_at: [],
    roleId: [],
  });

  const navigate = useNavigate();

  const [updateForm, setUpdateForm] = useState<UserUpdateRequest>({
    userCode: "",
    name: "",
    name_kana: "",
    email: "",
    password: "",
    password_confirmation: "",
    phone: "",
    position: "",
    status: "active",
    joined_at: "",
    resigned_at: "",
    roleId: "",
  });

  const handleSubmit = async () => {
    setError("");
    setErrors({});

    if (userCode === undefined) {
      return;
    }

    try {
      await userUpdate(userCode, updateForm);

      navigate("/user");
    } catch (err) {
      if (Axios.isAxiosError<ValidationErrorResponse>(err) && err.response?.status === 422) {
        const validationErrors = err.response.data.errors;
        setErrors(validationErrors);
      } else {
        setError("ユーザー登録に失敗しました");
      }
    }
  };

  const handleChange = (key: keyof UserUpdateRequest, value: string) => {
    setUpdateForm({
      ...updateForm,
      [key]: value,
    });
  };

  const fetchUser = async () => {
    setError("");

    if (userCode === undefined) {
      return;
    }

    try {
      const response = await userShow(userCode);

      const status = response.data.status;

      const validStatus = status === "active" || status === "leave" || status === "resigned";

      if (!validStatus) {
        setError("不正なステータスです");
        return;
      }

      setUpdateForm({
        userCode: response.data.userCode,
        name: response.data.name,
        name_kana: response.data.nameKana ?? "",
        email: response.data.email,
        phone: response.data.phone,
        position: response.data.position ?? "",
        status: status,
        joined_at: response.data.joined_at,
        resigned_at: response.data.resigned_at ?? "",
        roleId: String(response.data.roles?.[0]?.id ?? ""),
      });
    } catch {
      setError("ユーザー登録に失敗しました");
    }
  };

  useEffect(() => {
    fetchUser();
  }, [userCode]);

  return {
    updateForm,
    handleChange,
    error,
    errors,
    handleSubmit,
  };
};
