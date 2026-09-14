/* The Ebook Edit — floating WhatsApp button.
   ------------------------------------------------------------------
   The approved landing page's WhatsApp behaviour, lifted out of
   landing.js so the thank-you page can carry the same button without
   also loading the carousel and the lead form. There is one
   implementation, shared by both templates.

   The number and message come from PHP in window.teebeLanding. If no
   number is configured the approved fallback still applies: a short
   notice, then the visitor is taken to the page's own enquiry anchor
   when it has one.
   ------------------------------------------------------------------ */

(() => {
  const config = window.teebeLanding || {};
  const number = config.whatsappNumber || "";
  const message = config.whatsappMessage ||
    "Hello The Ebook Edit, I would like to discuss an ebook project.";

  const whatsapp = document.getElementById("whatsapp");
  if (!whatsapp) return;
  const toast = document.getElementById("toast");

  whatsapp.addEventListener("click", e => {
    if (number) {
      e.preventDefault();
      window.open(`https://wa.me/${number}?text=${encodeURIComponent(message)}`, "_blank", "noopener,noreferrer");
      return;
    }

    e.preventDefault();
    if (toast) {
      toast.classList.add("show");
      setTimeout(() => toast.classList.remove("show"), 2600);
    }
    // The landing page has an enquiry form to fall back to; the
    // thank-you page does not, and simply shows the notice.
    const contact = document.getElementById("contact");
    if (contact) setTimeout(() => contact.scrollIntoView({behavior: "smooth"}), 650);
  });
})();
