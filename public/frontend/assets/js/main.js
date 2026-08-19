/* =========================================================
   Project & Task Management System — base JS
   Plain JS + Bootstrap 5 JS. No framework.
   Replace the "TODO (AJAX)" blocks with real fetch() calls
   to your Laravel routes / API when you wire this up.
========================================================= */

document.addEventListener("DOMContentLoaded", function () {
  initSidebarToggle();
  initKanbanDragDrop();
  initCommentForm();
  initFileUploadPreview();
  initDeleteConfirm();
});

/* ---------------------------------------------------------
   Sidebar toggle (mobile / collapse button)
--------------------------------------------------------- */
function initSidebarToggle() {
  const toggleBtn = document.getElementById("sidebarToggle");
  if (!toggleBtn) return;

  toggleBtn.addEventListener("click", function () {
    document.body.classList.toggle("sidebar-collapsed");
  });
}

/* ---------------------------------------------------------
   Kanban board: drag & drop task cards between status columns
--------------------------------------------------------- */
function initKanbanDragDrop() {
  const board = document.querySelector(".kanban-board");
  if (!board) return;

  const cards = board.querySelectorAll(".task-card");
  const columns = board.querySelectorAll(".kanban-column-body");

  cards.forEach((card) => {
    card.addEventListener("dragstart", function () {
      card.classList.add("dragging");
    });
    card.addEventListener("dragend", function () {
      card.classList.remove("dragging");
    });
  });

  columns.forEach((column) => {
    column.addEventListener("dragover", function (e) {
      e.preventDefault();
      column.classList.add("drag-over");
    });

    column.addEventListener("dragleave", function () {
      column.classList.remove("drag-over");
    });

    column.addEventListener("drop", function (e) {
      e.preventDefault();
      column.classList.remove("drag-over");

      const dragging = board.querySelector(".dragging");
      if (!dragging) return;

      column.appendChild(dragging);

      const newStatus = column.closest(".kanban-column").dataset.status;
      const taskId = dragging.dataset.taskId;
      updateColumnCount(dragging.closest(".kanban-column"));
      updateColumnCount(column.closest(".kanban-column"));

      // TODO (AJAX): persist the new status to the backend, e.g.
      // fetch(`/tasks/${taskId}/status`, {
      //   method: "PATCH",
      //   headers: {
      //     "Content-Type": "application/json",
      //     "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
      //   },
      //   body: JSON.stringify({ status: newStatus })
      // });
      console.log(`Task #${taskId} moved to "${newStatus}"`);
    });
  });

  // Initialize counts on load
  document.querySelectorAll(".kanban-column").forEach(updateColumnCount);
}

function updateColumnCount(columnEl) {
  if (!columnEl) return;
  const countBadge = columnEl.querySelector(".column-count");
  const count = columnEl.querySelectorAll(".task-card").length;
  if (countBadge) countBadge.textContent = count;
}

/* ---------------------------------------------------------
   Comment form: append comment to the list (task-show page)
--------------------------------------------------------- */
function initCommentForm() {
  const form = document.getElementById("commentForm");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const textarea = form.querySelector("textarea");
    const text = textarea.value.trim();
    if (!text) return;

    const list = document.getElementById("commentList");
    const emptyState = list.querySelector(".empty-state");
    if (emptyState) emptyState.remove();

    const item = document.createElement("div");
    item.className = "comment-item d-flex gap-3";
    item.innerHTML = `
      <div class="avatar-circle">You</div>
      <div class="flex-grow-1">
        <div class="d-flex justify-content-between">
          <strong>You</strong>
          <span class="text-muted small">just now</span>
        </div>
        <p class="mb-0 mt-1">${escapeHtml(text)}</p>
      </div>
    `;
    list.appendChild(item);
    textarea.value = "";

    // TODO (AJAX): POST to /tasks/{task}/comments
  });
}

function escapeHtml(str) {
  const div = document.createElement("div");
  div.textContent = str;
  return div.innerHTML;
}

/* ---------------------------------------------------------
   File upload: show selected file name(s) before submit
--------------------------------------------------------- */
function initFileUploadPreview() {
  const input = document.getElementById("attachmentInput");
  if (!input) return;

  input.addEventListener("change", function () {
    const listEl = document.getElementById("attachmentPreviewList");
    if (!listEl) return;
    listEl.innerHTML = "";

    Array.from(input.files).forEach((file) => {
      const sizeKb = (file.size / 1024).toFixed(1);
      const li = document.createElement("li");
      li.className = "list-group-item d-flex justify-content-between align-items-center";
      li.innerHTML = `
        <span><i class="bi bi-paperclip me-2"></i>${escapeHtml(file.name)}</span>
        <span class="text-muted small">${sizeKb} KB</span>
      `;
      listEl.appendChild(li);
    });
  });
}

/* ---------------------------------------------------------
   Generic "delete" confirmation modal trigger
   Usage: add data-confirm-delete="Project name" to a button
--------------------------------------------------------- */
function initDeleteConfirm() {
  document.querySelectorAll("[data-confirm-delete]").forEach((btn) => {
    btn.addEventListener("click", function (e) {
      const label = btn.getAttribute("data-confirm-delete") || "this item";
      const ok = confirm(`Are you sure you want to delete ${label}? This cannot be undone.`);
      if (!ok) {
        e.preventDefault();
        e.stopPropagation();
      }
    });
  });
}
