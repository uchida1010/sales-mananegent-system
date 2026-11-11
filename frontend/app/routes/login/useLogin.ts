import { useState, useEffect, useRef } from "react";
import { sanctumCsrfCookie, loginStore, me } from "~/api/salesManagementSystem";
import { useNavigate } from "react-router";
import Axios from "axios";

export const useLogin = () => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [remember, setRemember] = useState(true);
  const [error, setError] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);
  const navigate = useNavigate();
  const isSubmittingRef = useRef(false);

  const handleSubmit = async () => {
    if (isSubmittingRef.current) {
      return;
    }

    isSubmittingRef.current = true;

    setError("");
    setIsSubmitting(true);

    try {
      await sanctumCsrfCookie();

      await loginStore({
        email,
        password,
        remember,
      });

      await me();

      navigate("/user");
    } catch (error) {
      if (Axios.isAxiosError(error) && error.response?.status === 422) {
        setError("メールアドレスまたはパスワードが正しくありません");
        return;
      }

      if (Axios.isAxiosError(error) && error.response?.status === 419) {
        setError("セッションの有効期限が切れました。もう一度ログインしてください");
        return;
      }

      if (Axios.isAxiosError(error) && error.response?.status === 429) {
        setError("ログイン試行回数が上限に達しました。しばらく時間をおいてから再度お試しください");
        return;
      }

      if (Axios.isAxiosError(error) && !error.response) {
        setError("サーバーに接続できません。通信環境を確認してから再度お試しください。");
        return;
      }

      setError("ログイン処理に失敗しました");
    } finally {
      setIsSubmitting(false);
      isSubmittingRef.current = false;
    }
  };

  useEffect(() => {
    const checkAuth = async () => {
      try {
        await me();

        navigate("/user");
      } catch (error) {
        if (Axios.isAxiosError(error) && error.response?.status === 401) {
          return;
        }

        setError("認証状態の確認に失敗しました");
      }
    };

    checkAuth();
  }, [navigate]);

  return {
    email,
    setEmail,
    password,
    setPassword,
    remember,
    setRemember,
    handleSubmit,
    isSubmitting,
    error,
  };
};
