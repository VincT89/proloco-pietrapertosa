export function getLang(searchParams) {
  return searchParams?.lang === "en" ? "en" : "it";
}

export function withLang(path, lang) {
  return lang === "en" ? `${path}?lang=en` : path;
}
