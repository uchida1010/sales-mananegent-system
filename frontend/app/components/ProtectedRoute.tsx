import { useEffect, useState } from "react";
import { Outlet } from "react-router";
import { me } from "~/api/salesManagementSystem";
import Axios from "axios";
import { useNavigate } from "react-router";

export default function ProtectedRoute() {
  const [isLoading, setIsLoading] = useState(true);
  const navigate = useNavigate();
  const [error, setError] = useState("");

  useEffect(() => {
    const checkAuth = async () => {
      try {
        await me();
        setIsLoading(false);
      } catch (error) {
        if (Axios.isAxiosError(error) && error.response?.status === 401) {
          navigate("/login", { replace: true });
          return;
        }

        setError("認証状態の確認に失敗しました");
        setIsLoading(false);
      }
    };

    void checkAuth();
  }, [navigate]);

  if (isLoading) {
    return <p>認証確認中...</p>;
  }

  if (error) {
    return <p>{error}</p>;
  }

  return <Outlet />;
}
