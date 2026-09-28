import { useState } from "react";

import { userStore, type UserStoreRequest } from "~/api/salesManagementSystem";
import { useNavigate } from "react-router";
import Axios from "axios"

export type ValidationErrors = {
    userCode?: string[];
    name?: string[];
    name_kana?: string[];
    email?: string[];
    phone?: string[];
    position?: string[];
    joined_at?: string[];
    roleId?: string[];
};

export type ValidationErrorResponse = {
    message: string;
    errors: ValidationErrors;
};

export const useUserCreate = () => {

    const [storeForm, setStoreForm] = useState<UserStoreRequest>({
        userCode: "",
        name: "",
        name_kana: "",
        email: "",
        phone: "",
        position: "",
        joined_at: "",
        roleId: "",
    });

    const handleChange = (key: keyof UserStoreRequest, value: string) => {
        setStoreForm({
            ...storeForm,
            [key]: value,
        });
    };

    const [error, setError] = useState("");

    const [errors, setErrors] = useState<ValidationErrors>({
        userCode: [],
        name: [],
        name_kana: [],
        email: [],
        phone: [],
        position: [],
        joined_at: [],
        roleId: [],
    });

    const navigate = useNavigate();

    const handleSubmit = async () => {
        setError("");
        setErrors({});

        try {
            await userStore(storeForm);

            navigate("/user");
        } catch (error) {
            if (Axios.isAxiosError<ValidationErrorResponse>(error) && error.response?.status === 422) {
                const validationErrors = error.response.data.errors;
                setErrors(validationErrors);
            } else {
                setError("ユーザー登録に失敗しました");
            }
        }
    };

    return {
        storeForm,
        handleChange,
        error,
        errors,
        handleSubmit,
    };

};