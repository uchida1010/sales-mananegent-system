import type { Route } from "./+types/samepage";
import "../app.css";
import { DoubleNavbar } from "../components/DoubleNavbar";

export function meta({}: Route.MetaArgs) {
  return [
    { title: "Sample Page" },
    { name: "description", content: "Sample Page" },
  ];
}

export default function SamplePage() {
  return (
    <>
    <DoubleNavbar />
      <main>
        <h1>ホーム画面</h1>
      </main>
    </>
  );
}
