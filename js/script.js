const steps = document.querySelectorAll(".form-step");
const nextBtns = document.querySelectorAll(".next-btn");
const prevBtns = document.querySelectorAll(".prev-btn");
const progressSteps = document.querySelectorAll(".step");
const progressFill = document.querySelector(".progress-fill");
const detailZakatDiv = document.getElementById("detail-zakat");
let currentStep = 0;

const hartaInput = document.getElementById("jumlah-harta");
  const nisabInput = document.getElementById("nisab");
  const zakatOutput = document.getElementById("jumlah-zakat");

// Function to show the current step
function showStep(step) {
  steps.forEach((s, index) => {
    s.classList.toggle("active", index === step);
    progressSteps[index].classList.toggle("active", index === step);
  });
  updateProgressBar(step);
}

// Function to update the progress bar fill
function updateProgressBar(step) {
  const totalSteps = steps.length;
  const progressPercentage = ((step + 1) / totalSteps) * 100; // Calculate percentage
  progressFill.style.width = `${progressPercentage}%`; // Set width of the fill
}

// Function to handle next button click
nextBtns.forEach(btn => {
  btn.addEventListener("click", () => {
    if (validateStep(currentStep)) {
      currentStep++;
      showStep(currentStep);
      updateDetailZakat();
    }
  });
});
if (currentStep === steps.length - 2) {
    const preview = document.getElementById("previewData");
    const nama = document.querySelector("input[name='nama']").value;
    const zakat = document.querySelector("select[name='jenis_zakat']").value;
    preview.innerHTML = `
      <p><strong>Nama:</strong> ${nama}</p>
      <p><strong>Jenis Zakat:</strong> ${zakat}</p>
      <!-- Tambahkan data lain sesuai input -->
    `;
  }
  

// Function to handle previous button click
prevBtns.forEach(btn => {
  btn.addEventListener("click", () => {
    currentStep--;
    showStep(currentStep);
  });
});

// Function to validate the current step
function validateStep(step) {
  const inputs = steps[step].querySelectorAll("input, select");
  let valid = true;

  inputs.forEach(input => {
    if (input.required && !input.value) {
      input.classList.add("error");
      valid = false;
    } else {
      input.classList.remove("error");
    }
  });

  return valid;
}

// Function to update detail zakat based on selected jenis zakat
function updateDetailZakat() {
  const jenisZakatSelect = document.querySelector("select[name='jenis_zakat']");
  const selectedValue = jenisZakatSelect.value;

  detailZakatDiv.innerHTML = ""; // Clear previous details

  if (selectedValue === "mal") {
    detailZakatDiv.innerHTML = `
      <label>Jumlah Zakat Mal:</label>
      <input type="number" name="jumlah_zakat_mal" required placeholder="Masukkan jumlah zakat mal">
    `;
  } else if (selectedValue === "fitrah") {
    detailZakatDiv.innerHTML = `
      <label>Jumlah Zakat Fitrah:</label>
      <input type="number" name="jumlah_zakat_fitrah" required placeholder="Masukkan jumlah zakat fitrah">
    `;
  } else if (selectedValue === "peternakan") {
    detailZakatDiv.innerHTML = `
      <label>Jumlah Ternak:</label>
      <input type="number" name="jumlah_ternak" required placeholder="Masukkan jumlah ternak">
      <label>Jenis Ternak:</label>
      <select name="jenis_ternak" required>
        <option value="kambing">Kambing</option>
        <option value="sapi">Sapi</option>
        <option value="unta">Unta</option>
      </select>
    `;
  }
  
}

// Initialize the first step
showStep(currentStep);
