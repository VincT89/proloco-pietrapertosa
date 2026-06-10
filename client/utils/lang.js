export function getLang(searchParams) {
  if (!searchParams) return "it";

  if (typeof searchParams.get === "function") {
    return searchParams.get("lang") === "en" ? "en" : "it";
  }

  return searchParams.lang === "en" ? "en" : "it";
}

export function withLang(path, lang) {
  if (!path) return lang === "en" ? "/?lang=en" : "/";

  const [pathWithoutHash, hash] = path.split("#");
  const [basePath, queryString] = pathWithoutHash.split("?");

  const params = new URLSearchParams(queryString || "");

  if (lang === "en") {
    params.set("lang", "en");
  } else {
    params.delete("lang");
  }

  const query = params.toString();
  const finalPath = query ? `${basePath}?${query}` : basePath;

  return hash ? `${finalPath}#${hash}` : finalPath;
}
