import { useRef, useState } from "react";
import { logout } from "~/api/salesManagementSystem";
import Axios from "axios";
import { useNavigate } from "react-router";

export const useLogout = () => {
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [error, setError] = useState("");
  const isSubmittingRef = useRef(false);
  const navigate = useNavigate();

  const handleLogout = async () => {
    if (isSubmittingRef.current) {
      return;
    }

    isSubmittingRef.current = true;

    setIsSubmitting(true);

    setError("");

    try {
      await logout();

      navigate("/login");
    } catch (error) {
      if (Axios.isAxiosError(error) && error.response?.status === 401) {
        navigate("/login");
        return;
      }

      setError("ログアウトに失敗しました");
    } finally {
      setIsSubmitting(false);
      isSubmittingRef.current = false;
    }
  };

  return {
    handleLogout,
    isSubmitting,
    error,
  };
};
