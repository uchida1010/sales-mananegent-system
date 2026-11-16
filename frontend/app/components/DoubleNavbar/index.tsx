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
  IconLogout,
} from "@tabler/icons-react";
import { Title, Tooltip, UnstyledButton } from "@mantine/core";
import classes from "./DoubleNavbar.module.css";
import { useLogout } from "../../hooks/useLogout";

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

  const { handleLogout, isSubmitting, error } = useLogout();

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
    <UnstyledButton
      type="button"
      className={classes.link}
      data-active={activeLink === link || undefined}
      onClick={() => {
        setActiveLink(link);
      }}
      key={link}
    >
      {link}
    </UnstyledButton>
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

          <div className={classes.logoutArea}>
            <UnstyledButton
              type="button"
              className={classes.logoutButton}
              onClick={handleLogout}
              disabled={isSubmitting}
            >
              <IconLogout size={20} stroke={1.5} />

              <span>{isSubmitting ? "ログアウト中..." : "ログアウト"}</span>
            </UnstyledButton>

            {error && (
              <p className={classes.logoutError} role="alert">
                {error}
              </p>
            )}
          </div>
        </div>
      </div>
    </nav>
  );
}
