{
  const e = document.body;
  e.addEventListener("contextmenu", (e) => e.preventDefault()),
    e.addEventListener("copy", (e) => e.preventDefault()),
    e.addEventListener("cut", (e) => e.preventDefault()),
    e.addEventListener("paste", (e) => e.preventDefault());
}
