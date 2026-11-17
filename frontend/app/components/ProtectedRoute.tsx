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
    return (
      <div className="flex min-h-screen flex-col items-center justify-center gap-4 bg-slate-50">
        <div
          className="h-10 w-10 animate-spin rounded-full border-4 border-slate-200 border-t-blue-600"
          role="status"
          aria-label="認証確認中"
        />

        <p className="text-sm font-medium text-slate-600">認証確認中...</p>
      </div>
    );
  }

  if (error) {
    return <p>{error}</p>;
  }

  return <Outlet />;
}
