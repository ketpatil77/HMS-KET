(function () {
  var root = document.documentElement;
  var storageKey = 'hms-theme';
  var toastStack;
  var dialog;

  function getBaseUrl() {
    if (window.rs_obj && window.rs_obj.url) return window.rs_obj.url;
    if (window.BASE_URL) return window.BASE_URL;
    return '/';
  }

  function resolveUrl(url) {
    if (!url) return getBaseUrl();
    if (/^https?:\/\//i.test(url) || url.indexOf('//') === 0) return url;
    return getBaseUrl() + url.replace(/^\//, '');
  }

  function setTheme(theme) {
    root.setAttribute('data-theme', theme);
    try {
      localStorage.setItem(storageKey, theme);
    } catch (err) {}
    updateThemeButtons(theme);
  }

  function getInitialTheme() {
    try {
      var saved = localStorage.getItem(storageKey);
      if (saved === 'dark' || saved === 'light') return saved;
    } catch (err) {}
    return 'dark';
  }

  function updateThemeButtons(theme) {
    var nodes = document.querySelectorAll('[data-theme-toggle]');
    nodes.forEach(function (node) {
      node.innerHTML = theme === 'dark'
        ? '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A8.5 8.5 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z"/></svg>'
        : '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2.5"/><path d="M12 19.5V22"/><path d="M4.9 4.9l1.8 1.8"/><path d="M17.3 17.3l1.8 1.8"/><path d="M2 12h2.5"/><path d="M19.5 12H22"/><path d="M4.9 19.1l1.8-1.8"/><path d="M17.3 6.7l1.8-1.8"/></svg>';
    });
  }

  function toggleSidebar() {
    if (window.innerWidth < 992) {
      document.body.classList.toggle('nav-open');
      return;
    }
    document.body.classList.toggle('nav-sm');
  }

  function ensureToastStack() {
    if (toastStack) return toastStack;
    toastStack = document.createElement('div');
    toastStack.className = 'toast-stack';
    document.body.appendChild(toastStack);
    return toastStack;
  }

  function toast(message, tone) {
    var stack = ensureToastStack();
    var node = document.createElement('div');
    node.className = 'toast';
    node.dataset.tone = tone || 'info';
    node.textContent = message;
    stack.appendChild(node);
    window.setTimeout(function () {
      node.style.opacity = '0';
      node.style.transform = 'translateY(4px)';
      node.style.transition = 'opacity 180ms ease, transform 180ms ease';
      window.setTimeout(function () {
        if (node.parentNode) node.parentNode.removeChild(node);
      }, 180);
    }, 4000);
  }

  function countUp(node) {
    var target = Number(node.getAttribute('data-count-to') || node.textContent.replace(/[^\d.-]/g, ''));
    if (!isFinite(target)) return;
    var duration = Number(node.getAttribute('data-count-duration') || 900);
    var start = performance.now();
    var prefix = node.getAttribute('data-count-prefix') || '';
    var suffix = node.getAttribute('data-count-suffix') || '';

    function frame(now) {
      var progress = Math.min((now - start) / duration, 1);
      var value = Math.round(target * progress).toLocaleString();
      node.textContent = prefix + value + suffix;
      if (progress < 1) window.requestAnimationFrame(frame);
    }

    window.requestAnimationFrame(frame);
  }

  function setSidebarState() {
    var toggle = document.querySelector('[data-sidebar-toggle]');
    if (!toggle) return;
    if (window.innerWidth < 992) {
      document.body.classList.remove('nav-sm');
    }
  }

  function setActiveNav() {
    var current = window.location.href.split('#')[0].split('?')[0];
    document.querySelectorAll('.nav.side-menu a, .sidebar-link, .front-nav a').forEach(function (link) {
      var href = link.href && link.href.split('#')[0].split('?')[0];
      if (!href) return;
      if (href === current) {
        link.classList.add('active');
        var item = link.closest('li');
        if (item) item.classList.add('current-page', 'active');
      }
    });
  }

  function closeDialog() {
    if (!dialog) return;
    dialog.classList.remove('is-open');
    dialog.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('modal-open');
  }

  function openDialog(title, bodyHtml) {
    if (!dialog) return;
    var titleNode = dialog.querySelector('[data-dialog-title]');
    var bodyNode = dialog.querySelector('[data-dialog-body]');
    if (titleNode) titleNode.textContent = title || 'Dialog';
    if (bodyNode) bodyNode.innerHTML = bodyHtml || '<div class="text-center small-muted">Loading...</div>';
    dialog.classList.add('is-open');
    dialog.setAttribute('aria-hidden', 'false');
    document.body.classList.add('modal-open');
  }

  function bindDialog() {
    dialog = document.getElementById('rs_dialog');
    if (!dialog) return;

    dialog.addEventListener('click', function (event) {
      if (event.target === dialog || event.target.matches('[data-dialog-close]')) {
        closeDialog();
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') closeDialog();
    });

    document.body.addEventListener('click', function (event) {
      var opener = event.target.closest('.dialog_open');
      if (!opener) return;
      event.preventDefault();
      var url = opener.getAttribute('data-url');
      var title = opener.getAttribute('data-title') || 'Dialog';
      var json = opener.getAttribute('data-json') || '';
      var bodyNode = dialog.querySelector('[data-dialog-body]');
      openDialog(title, '<div class="text-center small-muted">Loading...</div>');

      fetch(resolveUrl(url), {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(function (response) { return response.text(); })
        .then(function (html) {
          if (bodyNode) {
            bodyNode.innerHTML = html;
            var form = bodyNode.querySelector('#rs_form_prescription');
            if (form && json) {
              form.dataset.json = json;
            }
            bindPrescriptionForm(bodyNode);
          }
        })
        .catch(function () {
          if (bodyNode) bodyNode.innerHTML = '<div class="alert alert-danger">Unable to load content.</div>';
        });
    });
  }

  function bindPrescriptionForm(scope) {
    var form = scope.querySelector('#rs_form_prescription');
    if (!form || form.dataset.bound === '1') return;
    form.dataset.bound = '1';
    form.addEventListener('submit', function (event) {
      event.preventDefault();
      var payload = {
        data: form.dataset.json || '',
        prescription: form.querySelector('#prescription') ? form.querySelector('#prescription').value : ''
      };
      fetch(getBaseUrl() + 'Doctors/AddprescriptionSave', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams(payload).toString()
      }).then(function () {
        window.location.reload();
      });
    });
  }

  function bindDeleteConfirm() {
    document.body.addEventListener('click', function (event) {
      var link = event.target.closest('.delete_confirm');
      if (!link) return;
      if (link.classList.contains('dialog_open')) return;
      var message = link.getAttribute('data-confirm') || 'Delete this record?';
      if (!window.confirm(message)) {
        event.preventDefault();
      }
    });
  }

  function bindScheduleSelect() {
    document.body.addEventListener('click', function (event) {
      var button = event.target.closest('.btn_schedule_select_btn');
      if (!button) return;
      document.querySelectorAll('.schedule-card').forEach(function (card) {
        card.classList.remove('is-selected');
      });
      var card = button.closest('.schedule-card');
      if (card) {
        card.classList.add('is-selected');
        var radio = card.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
      }
    });
  }

  function bindCollapseLinks() {
    document.body.addEventListener('click', function (event) {
      var link = event.target.closest('.collapse-link');
      if (!link) return;
      event.preventDefault();
      var panel = link.closest('.x_panel');
      if (!panel) return;
      var content = panel.querySelector('.x_content');
      if (!content) return;
      var hidden = content.style.display === 'none';
      content.style.display = hidden ? '' : 'none';
      var icon = link.querySelector('i');
      if (icon) {
        icon.classList.toggle('fa-chevron-up');
        icon.classList.toggle('fa-chevron-down');
      }
    });
  }

  function bindSidebarToggle() {
    document.body.addEventListener('click', function (event) {
      var toggle = event.target.closest('[data-sidebar-toggle]');
      if (!toggle) return;
      event.preventDefault();
      toggleSidebar();
    });
  }

  function bindThemeToggle() {
    document.body.addEventListener('click', function (event) {
      var toggle = event.target.closest('[data-theme-toggle]');
      if (!toggle) return;
      event.preventDefault();
      var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      setTheme(next);
      toast('Theme switched to ' + next, 'info');
    });
  }

  function bindCounters() {
    document.querySelectorAll('[data-count-to]').forEach(function (node) {
      countUp(node);
    });
  }

  function bindQuickUi() {
    document.querySelectorAll('[data-stagger] > *').forEach(function (card, index) {
      card.style.animationDelay = (index * 50) + 'ms';
    });
  }

  function bindInvoiceAddRow() {
    var button = document.getElementById('btn_new_item');
    var container = document.getElementById('invoice_items');
    if (!button || !container) return;
    button.addEventListener('click', function (event) {
      event.preventDefault();
      var row = document.createElement('div');
      row.className = 'row';
      row.innerHTML = [
        '<div class="col-md-6">',
        '<input name="items_name[]" required="required" class="form-control" type="text" placeholder="Consultation, lab test, medicine">',
        '</div>',
        '<div class="col-md-6">',
        '<input name="items_price[]" class="form-control" type="number" min="0" step="0.01" placeholder="Amount in INR">',
        '</div>'
      ].join('');
      container.appendChild(row);
      toast('Medical item row added', 'success');
    });
  }

  function bindUploader() {
    var form = document.getElementById('upload');
    if (!form) return;
    var input = form.querySelector('input[type="file"]');
    var browse = form.querySelector('[data-upload-browse]');
    var drop = form.querySelector('#drop');
    var list = form.querySelector('ul');

    function addRow(label, tone) {
      if (!list) return;
      var item = document.createElement('li');
      item.innerHTML = '<strong>' + label + '</strong>';
      if (tone) item.dataset.tone = tone;
      list.appendChild(item);
    }

    function triggerUpload() {
      if (typeof form.requestSubmit === 'function') {
        form.requestSubmit();
      } else {
        var event = document.createEvent('Event');
        event.initEvent('submit', true, true);
        form.dispatchEvent(event);
      }
    }

    if (browse && input) {
      browse.addEventListener('click', function () {
        input.click();
      });
    }

    if (input) {
      input.addEventListener('change', function () {
        if (input.files && input.files.length) {
          triggerUpload();
        }
      });
    }

    if (drop && input) {
      drop.addEventListener('dragover', function (event) {
        event.preventDefault();
        drop.classList.add('is-selected');
      });
      drop.addEventListener('dragleave', function () {
        drop.classList.remove('is-selected');
      });
      drop.addEventListener('drop', function (event) {
        event.preventDefault();
        drop.classList.remove('is-selected');
        if (event.dataTransfer.files && event.dataTransfer.files.length) {
          var dt = new DataTransfer();
          Array.prototype.forEach.call(event.dataTransfer.files, function (file) {
            dt.items.add(file);
          });
          input.files = dt.files;
          triggerUpload();
        }
      });
    }

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      var data = new FormData(form);
      fetch(form.action, {
        method: 'POST',
        body: data,
        credentials: 'same-origin'
      })
        .then(function (response) { return response.json(); })
        .then(function (json) {
          if (json && json.url) {
            addRow('Uploaded: ' + json.url.split('/').pop(), 'success');
            var sector = document.body.getAttribute('data-media-group-id');
            if (sector) {
              var target = document.getElementById(sector);
              if (target) {
                var field = target.querySelector('.form-control');
                if (field) field.value = json.url;
              }
            }
            toast('Upload complete', 'success');
            closeDialog();
          } else {
            toast('Upload failed', 'danger');
          }
        })
        .catch(function () {
          toast('Upload failed', 'danger');
        });
    });
  }

  function bindLogoutLinks() {
    document.body.addEventListener('click', function (event) {
      var link = event.target.closest('a[href*="logout"]');
      if (!link) return;
      if (link.dataset.skipConfirm === '1') return;
      if (link.closest('.front-nav')) return;
    });
  }

  function init() {
    setTheme(getInitialTheme());
    setSidebarState();
    setActiveNav();
    bindSidebarToggle();
    bindThemeToggle();
    bindCounters();
    bindQuickUi();
    bindDialog();
    bindDeleteConfirm();
    bindScheduleSelect();
    bindCollapseLinks();
    bindInvoiceAddRow();
    bindUploader();
    bindLogoutLinks();
    window.addEventListener('resize', setSidebarState);
    window.hmsToast = toast;
  }

  document.addEventListener('DOMContentLoaded', init);
})();
