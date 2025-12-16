function showSection(sectionId) {
  document.querySelectorAll("section").forEach(sec => sec.classList.remove("active"));
  document.getElementById(sectionId).classList.add("active");
}

document.getElementById('downloadForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const url = document.getElementById('videoUrl').value;

  const evtSource = new EventSource("download.php?videoUrl=" + encodeURIComponent(url));

  evtSource.onmessage = function(e) {
    let data = JSON.parse(e.data);
    let bar = document.getElementById('bar');
    let nameDiv = document.getElementById('videoName');

    if (data.title) nameDiv.textContent = "Ikutsitsa: " + data.title;
    if (data.percent) {
      bar.style.width = data.percent + "%";
      bar.textContent = data.percent + "%";
    }

    if (data.done) {
      evtSource.close();
      bar.style.width = "100%";
      bar.textContent = "Yatha!";
      window.location.href = "download.php?getFile=" + encodeURIComponent(data.done);
    }
  };
});