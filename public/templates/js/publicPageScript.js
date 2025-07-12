document.addEventListener("DOMContentLoaded", () => {
  const allCarousels = document.querySelectorAll(".carousel");

  allCarousels.forEach((carousel) => {
    const indicators = carousel.querySelectorAll(
      ".carousel-indicators [data-bs-target]"
    );

    function updateIndicators() {
      const activeIndex = [...indicators].findIndex((dot) =>
        dot.classList.contains("active")
      );

      indicators.forEach((dot, i) => {
        dot.classList.remove("active-dot", "near-active-dot");
        if (i === activeIndex) {
          dot.classList.add("active-dot");
        } else if (Math.abs(i - activeIndex) <= 2) {
          dot.classList.add("near-active-dot");
        }
      });
    }

    // Initial indicator styling
    updateIndicators();

    // Update indicators on slide
    carousel.addEventListener("slid.bs.carousel", updateIndicators);
  });
});

function setupImageUploader(previewContainerId, finalInputId) {
  const previewContainer = document.getElementById(previewContainerId);
  const finalImageInput = document.getElementById(finalInputId);

  const imageInput = document.createElement("input");
  imageInput.type = "file";
  imageInput.accept = "image/*";
  imageInput.multiple = true;

  const uploadPlaceholder = document.createElement("div");
  uploadPlaceholder.className = "upload-placeholder";
  uploadPlaceholder.title = "Add Photo";
  uploadPlaceholder.innerHTML = '<i class="bi bi-plus-lg"></i>';
  uploadPlaceholder.appendChild(imageInput);

  let fileList = [];

  imageInput.addEventListener("change", function () {
    [...this.files].forEach((file) => fileList.push(file));
    this.value = "";
    renderPreviews();
  });

  function renderPreviews() {
    previewContainer.innerHTML = "";

    fileList.forEach((file, index) => {
      const wrapper = document.createElement("div");
      wrapper.className = "preview-container";
      wrapper.draggable = true;
      wrapper.dataset.index = index;

      const img = document.createElement("img");
      img.className = "image-preview";
      const reader = new FileReader();
      reader.onload = (e) => (img.src = e.target.result);
      reader.readAsDataURL(file);

      const removeBtn = document.createElement("button");
      removeBtn.className = "remove-btn";
      removeBtn.innerHTML = '<i class="bi bi-trash-fill"></i>';
      removeBtn.onclick = () => {
        fileList.splice(index, 1);
        renderPreviews();
      };

      wrapper.appendChild(img);
      wrapper.appendChild(removeBtn);

      wrapper.addEventListener("dragstart", (e) => {
        wrapper.classList.add("dragging");
        e.dataTransfer.setData("text/plain", index);
      });

      wrapper.addEventListener("dragend", () =>
        wrapper.classList.remove("dragging")
      );
      wrapper.addEventListener("dragover", (e) => e.preventDefault());
      wrapper.addEventListener("drop", (e) => {
        e.preventDefault();
        const draggedIndex = parseInt(e.dataTransfer.getData("text/plain"));
        const targetIndex = parseInt(wrapper.dataset.index);

        if (draggedIndex !== targetIndex) {
          const draggedItem = fileList.splice(draggedIndex, 1)[0];
          fileList.splice(targetIndex, 0, draggedItem);
          renderPreviews();
        }
      });

      previewContainer.appendChild(wrapper);
    });

    previewContainer.appendChild(uploadPlaceholder);

    const dt = new DataTransfer();
    fileList.forEach((file) => dt.items.add(file));
    finalImageInput.files = dt.files;
  }

  renderPreviews();
}

window.addEventListener("DOMContentLoaded", () => {
  // Auto padding for navbar height
  const navbar = document.querySelector(".main-header.navbar");
  if (navbar) {
    const navbarHeight = navbar.offsetHeight;
    document.body.style.paddingTop = `${navbarHeight}px`;
  }

  setupImageUploader("addImagePreview", "addFinalImageInput");
  setupImageUploader("editImagePreview", "editFinalImageInput");
});

$(document).ready(function () {
  function showLoader() {
    const loader = document.getElementById("loading-spinner");
    if (loader) {
      loader.style.display = "flex";
    }
  }

  function hideLoader() {
    const loader = document.getElementById("loading-spinner");
    if (loader) {
      loader.style.display = "none";
    }
  }

  // Function to toggle button based on textarea input
  function toggleSubmitButton(textareaId, buttonId) {
    $("#" + textareaId).on("input", function () {
      const isEmpty = $(this).val().trim() === "";
      $("#" + buttonId).prop("disabled", isEmpty);
    });
  }

  // Apply to Add Post
  toggleSubmitButton("addDescription", "submitPostBtn");

  // Apply to Edit Post
  toggleSubmitButton("editDescription", "editPostBtn");

  $("#addPostForm").on("submit", function (e) {
    e.preventDefault();
    $(this)
      .find('button[type="submit"]')
      .html('<i class="bi bi-upload"></i> Publishing...')
      .prop("disabled", true);
    showLoader();
    this.submit();
  });

  // Edit Post submit
  $("#editPostForm").on("submit", function (e) {
    e.preventDefault();
    $(this)
      .find('button[type="submit"]')
      .text("Saving...")
      .prop("disabled", true);
    showLoader();
  });
});
