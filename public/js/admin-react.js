document.addEventListener('DOMContentLoaded', function () {
    var rootEl = document.getElementById('admin-root');
    if (!rootEl || !window.React || !window.ReactDOM) {
        return;
    }

    var e = window.React.createElement;
    var useState = window.React.useState;
    var createRoot = window.ReactDOM.createRoot;

    var data = rootEl.dataset;
    var bladeContentEl = document.getElementById('blade-content');
    var bladeHtml = bladeContentEl ? bladeContentEl.innerHTML : '';

    var currentPath = window.location.pathname;

    function active(path) {
        return currentPath === path || currentPath.indexOf(path + '/') === 0;
    }

    function Shell() {
        var state = useState(false);
        var sidebarOpen = state[0];
        var setSidebarOpen = state[1];
        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
        var csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';
        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
        var csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';

        return e('div', null,
            e('header', { className: 'admin-header' },
                e('div', { className: 'header-left' },
                    e('i', {
                        className: 'uil uil-bars menu-toggle',
                        onClick: function () { setSidebarOpen(!sidebarOpen); }
                    }),
                    e('div', { className: 'logo' }, 'Hotel Admin')
                        e('form', { method: 'POST', action: data.logoutUrl },
                            e('input', { type: 'hidden', name: '_token', value: csrf }),
                            e('button', { className: 'logout-btn', type: 'submit' }, 'Logout')
                        )
                e('div', { className: 'header-right' },
                    e('a', { href: data.profileUrl },
                        e('img', {
                            src: data.adminPhoto,
                            className: 'avatar-small',
                            alt: 'Admin'
                        })
                    )
                )
            ),
            e('div', { className: 'admin-container' },
                e('aside', { className: 'admin-sidebar' + (sidebarOpen ? ' active' : ''), id: 'sidebar' },
                    e('div', { className: 'sidebar-top' },
                        e('img', { src: data.adminPhoto, className: 'avatar-large', alt: 'Admin' }),
                        e('h3', null, data.adminName || 'Admin')
                    ),
                    e('nav', { className: 'sidebar-menu' },
                        e('ul', null,
                            e('li', { className: active('/dashboard') ? 'active-link' : '' },
                                e('a', { href: data.dashboardUrl },
                                    e('i', { className: 'uil uil-apps' }),
                                    'Dashboard'
                                )
                            ),
                            e('li', { className: 'menu-item ' + (active('/customers') ? 'active' : '') },
                                e('div', { className: 'menu-title' },
                                    e('span', null,
                                        e('i', { className: 'uil uil-users-alt' }),
                                        'Customers'
                                    ),
                                    e('i', { className: 'uil uil-angle-left-b arrow' })
                                ),
                                e('ul', { className: 'submenu' },
                                    e('li', { className: currentPath === '/customers' ? 'active-link' : '' },
                                        e('a', { href: data.customersIndexUrl }, 'View Customer List')
                                    ),
                                    e('li', { className: currentPath === '/customers/create' ? 'active-link' : '' },
                                        e('a', { href: data.customersCreateUrl }, 'Add Customer')
                                    )
                                )
                            ),
                            e('li', { className: 'menu-item ' + (active('/hotels') ? 'active' : '') },
                                e('div', { className: 'menu-title' },
                                    e('span', null,
                                        e('i', { className: 'uil uil-bed-double' }),
                                        'Hotel'
                                    ),
                                    e('i', { className: 'uil uil-angle-left-b arrow' })
                                ),
                                e('ul', { className: 'submenu' },
                                    e('li', { className: currentPath === '/hotels' ? 'active-link' : '' },
                                        e('a', { href: data.hotelsIndexUrl }, 'View Hotel List')
                                    ),
                                    e('li', { className: currentPath === '/hotels/create' ? 'active-link' : '' },
                                        e('a', { href: data.hotelsCreateUrl }, 'Add Hotel')
                                    )
                                )
                            ),
                            e('li', { className: 'menu-item ' + (active('/bookings') ? 'active' : '') },
                                e('div', { className: 'menu-title' },
                                    e('span', null,
                                        e('i', { className: 'uil uil-invoice' }),
                                        'Booking'
                                    ),
                                    e('i', { className: 'uil uil-angle-left-b arrow' })
                                ),
                                e('ul', { className: 'submenu' },
                                    e('li', { className: currentPath === '/bookings' ? 'active-link' : '' },
                                        e('a', { href: data.bookingsIndexUrl }, 'View Booking List')
                                    ),
                                    e('li', { className: currentPath === '/bookings/create' ? 'active-link' : '' },
                                        e('a', { href: data.bookingsCreateUrl }, 'Add Booking')
                                    )
                                )
                            )
                        )
                    ),
                    e('div', { className: 'sidebar-bottom' },
                        e('form', { method: 'POST', action: data.logoutUrl },
                            e('input', { type: 'hidden', name: '_token', value: csrf }),
                            e('button', { className: 'logout-btn', type: 'submit' }, 'Logout')
                        )
                    )
                ),
                e('main', { className: 'admin-content' },
                    e('div', { dangerouslySetInnerHTML: { __html: bladeHtml } })
                )
            )
        );
    }

    createRoot(rootEl).render(e(Shell));
});
