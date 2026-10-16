(() => {
  const searchModal = document.getElementById('menuSearchModal');
  const filterMenus = () => {
    const term = document.getElementById('menuSearchInput').value.trim().toLocaleLowerCase('id-ID');
    const links = [...searchModal.querySelectorAll('[data-menu-search]')];
    links.forEach((a) => { a.hidden = !a.dataset.menuSearch.includes(term); });
    const count = links.filter((a) => !a.hidden).length;
    document.getElementById('menuSearchCount').textContent = `${count} menu ditemukan. Gunakan Tab untuk memilih hasil.`;
    searchModal.querySelector('[data-menu-search-empty]').hidden = !!count;
  };
  searchModal.addEventListener('show.bs.modal', filterMenus);
  document.getElementById('menuSearchInput').addEventListener('input', filterMenus);
  searchModal.addEventListener('shown.bs.modal', () => document.getElementById('menuSearchInput').focus());
  document.addEventListener('keydown', (event) => {
    if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
      event.preventDefault(); if (!document.querySelector('.modal.show,.offcanvas.show')) bootstrap.Modal.getOrCreateInstance(searchModal).show();
    }
    if (event.target.id === 'menuSearchInput' && event.key === 'Enter') { searchModal.querySelector('[data-menu-search]:not([hidden])')?.click(); event.preventDefault(); }
  });
})();
