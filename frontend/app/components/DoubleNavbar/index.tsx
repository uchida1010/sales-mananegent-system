import { useState } from "react";
import {
  IconGauge,
  IconUsers,
  IconBuildingStore,
  IconAddressBook,
  IconHistory,
  IconFileInvoice,
  IconFileImport,
  IconTable,
  IconPackage,
  IconTruck,
  IconSettings,
} from "@tabler/icons-react";
import { Title, Tooltip, UnstyledButton } from "@mantine/core";
import classes from "./DoubleNavbar.module.css";

const mainLinksMockdata = [
  { icon: IconGauge, label: "ダッシュボード" },
  { icon: IconUsers, label: "ユーザー管理" },
  { icon: IconBuildingStore, label: "取引先管理" },
  { icon: IconAddressBook, label: "連絡先" },
  { icon: IconHistory, label: "営業履歴" },
  { icon: IconFileInvoice, label: "受注" },
  { icon: IconFileImport, label: "発注" },
  { icon: IconTable, label: "在庫表" },
  { icon: IconPackage, label: "商品マスタ" },
  { icon: IconTruck, label: "配車依頼" },
  { icon: IconSettings, label: "設定" },
];

const linksMockdata = [
  "ダッシュボード",
  "ユーザー",
  "取引先",
  "連絡先",
  "営業履歴",
  "受注",
  "発注",
  "在庫表",
  "商品マスタ",
  "配車依頼",
  "設定",
];

export function DoubleNavbar() {
  const [active, setActive] = useState("販売管理システム");
  const [activeLink, setActiveLink] = useState("Settings");

  const mainLinks = mainLinksMockdata.map((link) => (
    <Tooltip
      label={link.label}
      position="right"
      withArrow
      transitionProps={{ duration: 0 }}
      key={link.label}
    >
      <UnstyledButton
        onClick={() => setActive(link.label)}
        className={classes.mainLink}
        data-active={link.label === active || undefined}
        aria-label={link.label}
      >
        <link.icon size={22} stroke={1.5} />
      </UnstyledButton>
    </Tooltip>
  ));

  const links = linksMockdata.map((link) => (
    <a
      className={classes.link}
      data-active={activeLink === link || undefined}
      href="#"
      onClick={(event) => {
        event.preventDefault();
        setActiveLink(link);
      }}
      key={link}
    >
      {link}
    </a>
  ));

  return (
    <nav className={classes.navbar}>
      <div className={classes.wrapper}>
        <div className={classes.aside}>
          <div className={classes.logo}></div>
          {mainLinks}
        </div>
        <div className={classes.main}>
          <Title order={4} className={classes.title}>
            {active}
          </Title>

          {links}
        </div>
      </div>
    </nav>
  );
}
