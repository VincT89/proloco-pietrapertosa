"use client";

import React, { useState, useEffect } from "react";
import { X } from "lucide-react";
import { useStore } from "./Store";

export default function LoginModal() {
  const { isLoginModalOpen, closeLoginModal, login } = useStore();
  const [pwd, setPwd] = useState("");
  const [err, setErr] = useState("");

  useEffect(() => {
    if (isLoginModalOpen) {
      setPwd("");
      setErr("");
      setTimeout(() => {
        document.getElementById("pwdInput")?.focus();
      }, 60);
    }
  }, [isLoginModalOpen]);

  if (!isLoginModalOpen) return null;

  const tryLogin = () => {
    if (login(pwd.trim())) {
      closeLoginModal();
    } else {
      setErr("Password sbagliata, riprovate.");
    }
  };

  return (
    <div className="modal open" id="loginModal">
      <div className="ov" onClick={closeLoginModal}></div>
      <div className="box">
        <button className="x" onClick={closeLoginModal} aria-label="Chiudi">
          <X className="ico" />
        </button>
        <img
          src="/images/logo.png"
          alt=""
          style={{ width: 110, height: "auto", margin: "0 auto 14px", display: "block" }}
        />
        <span className="lbl">solo per noi della Pro Loco</span>
        <h3>Area riservata</h3>
        <p className="hint">
          Password demo: <b>proloco2026</b>
        </p>
        <input
          type="password"
          id="pwdInput"
          placeholder="Password"
          autoComplete="current-password"
          value={pwd}
          onChange={(e) => setPwd(e.target.value)}
          onKeyDown={(e) => {
            if (e.key === "Enter") tryLogin();
          }}
        />
        <div className="err" id="pwdErr">
          {err}
        </div>
        <button className="btn-dark" onClick={tryLogin}>
          Accedi
        </button>
      </div>
    </div>
  );
}