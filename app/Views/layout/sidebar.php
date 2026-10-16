<?php
$activeMenu ??= 'dashboard';
$catalog = config(\Config\TemplateCatalog::class);
$initial = strtoupper(substr((string) $user['name'], 0, 1));
?>
<aside class="sidebar" id="sidebar">
    <a class="sidebar-brand spa-link" href="<?= site_url('dashboard') ?>" aria-label="SIPROSIR, Dashboard">
        <span class="brand-mark brand-mark-logo"><img src="<?= base_url('assets/img/siprosir-logo.png') ?>" alt=""></span>
        <span class="brand-text"><strong>SIPROSIR PRO V5</strong><small>SMART POS SYSTEM</small></span>
    </a>
    <nav class="sidebar-scroll" aria-label="Navigasi utama">
        <p class="sidebar-section-label">MENU UTAMA</p>
        <ul class="sidebar-nav">
            <li><a class="nav-link spa-link <?= $activeMenu === 'dashboard' ? 'active-menu' : '' ?>" data-menu="dashboard" href="<?= site_url('dashboard') ?>" aria-label="Dashboard"><i class="bi bi-speedometer2" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <?php foreach (service('accessPolicy')->groups() as $group): ?>
                <?php $groupOpen = in_array($activeMenu, array_column($group['items'], 'slug'), true); ?>
            <li class="nav-group <?= $groupOpen ? 'open active-branch' : '' ?>">
                <button class="nav-link nav-parent" type="button" aria-label="<?= esc($group['label']) ?>" aria-expanded="<?= $groupOpen ? 'true' : 'false' ?>">
                    <i class="bi <?= esc($group['icon'], 'attr') ?>"></i><span class="menu-label"><?= esc($group['label']) ?></span><i class="bi bi-chevron-right arrow-icon"></i>
                </button>
                <ul class="sidebar-submenu" data-flyout-title="<?= esc(strtoupper($group['label']), 'attr') ?>">
                    <?php foreach ($group['items'] as $item): ?>
                    <li><a class="nav-link spa-link <?= $activeMenu === $item['slug'] ? 'active-menu' : '' ?>" data-menu="<?= esc($item['slug'], 'attr') ?>" href="<?= site_url('katalog/' . $item['slug']) ?>"><i class="bi <?= esc($item['icon'], 'attr') ?>"></i><span><?= esc($item['label']) ?></span></a></li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <div class="sidebar-user"><span class="user-initial"><?= esc($initial) ?></span><span class="user-copy"><strong><?= esc($user['name']) ?></strong><small><?= esc(strtoupper($user['role'])) ?></small></span></div>
</aside>
