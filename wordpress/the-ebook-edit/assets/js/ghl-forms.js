/*
  The Ebook Edit — sizing the card around a HighLevel lead form.

  The approved embed carries height:100% and a declared data-height, and
  HighLevel's own form_embed.js writes an explicit pixel height onto the
  iframe element once the form has measured itself.

  Until that happens a percentage height has nothing to resolve against, so
  the stylesheet gives the wrapper the declared height: the card is the right
  size from the first frame and the page does not jump. The moment HighLevel
  writes the real height, this file hands control to it, so a form that turns
  out shorter leaves no dead space inside the card and one that turns out
  taller — a narrow screen stacks the fields — is never clipped.

  It watches the style attribute of the iframe *element*, which belongs to
  this page and which the HighLevel script writes to. Nothing here reads into
  the cross-origin document, listens for its submit, or tries to guess when a
  lead was captured: HighLevel's redirect to /thank-you/?conversion=lead is
  the only success signal the site uses.
*/
(() => {
  "use strict";

  const wraps = [...document.querySelectorAll(".ghl-form-wrap")];
  if (!wraps.length || typeof MutationObserver !== "function") return;

  wraps.forEach(wrap => {
    const frame = wrap.querySelector("iframe");
    if (!frame) return;

    // The approved embed ships height:100%. Anything else is HighLevel's own
    // measurement arriving.
    const measured = () => {
      const h = frame.style.height;
      return h !== "" && h !== "100%";
    };

    const release = () => wrap.classList.add("is-sized");

    if (measured()) {
      release();
      return;
    }

    const observer = new MutationObserver(() => {
      if (!measured()) return;
      release();
      observer.disconnect();
    });

    observer.observe(frame, { attributes: true, attributeFilter: ["style"] });

    // If the height never arrives — the library blocked, offline, or held
    // back by a consent manager — the declared height stays, which is the
    // right thing to show.
  });
})();
