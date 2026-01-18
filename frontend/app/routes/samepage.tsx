import type { Route } from "./+types/samepage";

export function meta({}: Route.MetaArgs) {
  return [
    { title: "Sample Page" },
    { name: "description", content: "Sample Page" },
  ];
}

export default function SamplePage() {
  return <div>This is a sample page.</div>;
}
