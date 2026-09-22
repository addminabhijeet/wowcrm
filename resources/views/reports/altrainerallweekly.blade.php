<script>
    document.getElementById("downloadPdfBtn").addEventListener("click", async function() {
        const btn = this;
        const originalText = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Generating PDF...';

        try {
            const element = document.getElementById("pdfContent");

            // ✅ Clone element to apply isolated print styles
            const clonedElement = element.cloneNode(true);

            // ✅ Add black & white print style dynamically (for PDF only)
            const printStyle = document.createElement("style");
            printStyle.textContent = `
            * {
                color: #000 !important;
                box-shadow: none !important;
                text-shadow: none !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                filter: contrast(250%) brightness(0%) !important;
            }
            body { background: #fff !important; margin: 0 !important; }
            h1, h2, h3, h4, h5, h6, p, label, span, small, th, td {
                color: #000 !important;
                font-weight: 800 !important;
                filter: contrast(250%) brightness(0%) !important;
            }
            .icon-wrapper {
                background: #fff !important;
                border: 2px solid #000 !important;
                border-radius: 50% !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            iconify-icon,
            i,
            .icon-wrapper iconify-icon {
                color: #000 !important;
                filter: grayscale(100%) contrast(200%) brightness(0%) !important;
            }
            [style*="background: linear-gradient"],
            [style*="background-color"] {
                background: #fff !important;
            }
            .card {
                background: #fff !important;
                border: 2px solid #000 !important;
                color: #000 !important;
                box-shadow: none !important;
                transition: none !important;
                filter: contrast(250%) brightness(0%) !important;
            }
            [onmouseover], [onmouseout] {
                transform: none !important;
                box-shadow: none !important;
            }
            table, th, td {
                border: 2px solid #000 !important;
                color: #000 !important;
                font-weight: 800 !important;
                background: #fff !important;
                -webkit-text-stroke: 0.3px #000 !important;
                filter: contrast(250%) brightness(0%) !important;
            }
            .badge {
                background: #ddd !important;
                color: #000 !important;
                font-weight: 900 !important;
                border: 2px solid #000 !important;
                padding: 4px 8px !important;
                filter: contrast(250%) brightness(0%) !important;
            }
            i, iconify-icon {
                color: #000 !important;
                filter: contrast(250%) brightness(0%) !important;
            }
            input, select, label {
                color: #000 !important;
                font-weight: 800 !important;
                -webkit-text-stroke: 0.2px #000 !important;
                filter: contrast(250%) brightness(0%) !important;
            }
            #semiCircleGauge, #areaChart, #dailyIconBarChart {
                background: #fff !important;
                min-height: 80px !important;
                filter: contrast(250%) brightness(0%) !important;
            }
            .card-body, .row, .col {
                padding: 10px !important;
                margin: 0 !important;
                filter: contrast(250%) brightness(0%) !important;
            }
            @page {
                size: A4 portrait;
                margin: 0;
            }
        `;
            clonedElement.prepend(printStyle);

            // Convert Iconify icons to inline SVG images for html2canvas visibility
            clonedElement.querySelectorAll("iconify-icon").forEach(icon => {
                const svg = document.createElement("img");
                const iconName = icon.getAttribute("icon");
                svg.src = `https://api.iconify.design/${iconName}.svg?color=%23000`;
                svg.width = 34;
                svg.height = 34;
                svg.style.filter = "contrast(250%) brightness(0%)";
                icon.replaceWith(svg);
            });

            // ✅ Proper A4 PDF dimensions in pixels
            const a4WidthPx = 1175;
            const a4HeightPx = Math.round(a4WidthPx * 1.4142);

            // ✅ Mount the cloned content off-screen so styles apply and layout computes
            //    (rendering the whole thing at once overflows the browser canvas limit
            //     at ~100 pages and produces a blank PDF, so we render each page alone)
            const offscreen = document.createElement("div");
            offscreen.style.position = "fixed";
            offscreen.style.left = "-99999px";
            offscreen.style.top = "0";
            offscreen.style.width = a4WidthPx + "px";
            offscreen.style.background = "#ffffff";
            offscreen.appendChild(clonedElement);
            document.body.appendChild(offscreen);

            // ✅ Wait for a short time to ensure all assets/styles load
            await new Promise(resolve => setTimeout(resolve, 50));

            // ✅ Render each report page individually into the same PDF
            const pages = clonedElement.querySelectorAll(".pdf-page");

            // Shared html2canvas settings (same quality as before)
            const html2canvasOpt = {
                scale: 3,
                useCORS: true,
                scrollY: 0,
                backgroundColor: "#ffffff",
                logging: false,
                letterRendering: true,
            };

            // Render a single element to a canvas using html2pdf's bundled html2canvas
            const renderCanvas = (el) =>
                html2pdf().set({
                    html2canvas: html2canvasOpt
                }).from(el).toCanvas().get('canvas');

            // ✅ Get a real jsPDF instance via html2pdf, then clear the auto-created
            //    page so EVERY page (including the first) is rendered the same way.
            const pdf = await html2pdf().set({
                margin: [0, 0, 0, 0],
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: html2canvasOpt,
                jsPDF: {
                    unit: 'px',
                    format: [a4WidthPx, a4HeightPx],
                    orientation: 'portrait'
                },
                pagebreak: {
                    mode: ['avoid-all', 'css', 'legacy']
                }
            }).from(pages[0]).toPdf().get('pdf');

            // ✅ Remove the auto-created page so every page uses the same A4 size
            pdf.deletePage(1);

            // ✅ Image size on the page (change these to resize the content).
            //    The page itself stays fixed at A4 (a4WidthPx x a4HeightPx).
            const imgWidth = 1175;
            const imgHeight = Math.round(a4WidthPx * 2.0);

            // ✅ Render every page through the identical path so the style matches.
            //    The page is always fixed A4; only the image size uses imgWidth/imgHeight.
            for (let i = 0; i < pages.length; i++) {
                const canvas = await renderCanvas(pages[i]);
                const imgData = canvas.toDataURL('image/jpeg', 0.98);

                // Page stays fixed A4; image uses the adjustable imgWidth/imgHeight.
                pdf.addPage([a4WidthPx, a4HeightPx], 'portrait');
                pdf.addImage(imgData, 'JPEG', 0, 0, imgWidth, imgHeight);
            }

            // ✅ Build the file name from the selected week -> Weekly_Report_<week>.pdf
            const selectedWeekInput = document.querySelector('input[name="selected_week"]');
            const selectedWeek = selectedWeekInput ? selectedWeekInput.value : '';
            let pdfFileName = 'weekly-report.pdf';
            if (selectedWeek) {
                pdfFileName = `Weekly_Report_${selectedWeek}.pdf`;
            }

            // ✅ Clean up the off-screen node and download with the correct filename
            document.body.removeChild(offscreen);

            // Force download with the correct filename
            const pdfBlob = pdf.output('blob');
            const pdfUrl = URL.createObjectURL(pdfBlob);
            const downloadLink = document.createElement('a');
            downloadLink.href = pdfUrl;
            downloadLink.download = pdfFileName;
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
            URL.revokeObjectURL(pdfUrl);
        } catch (error) {
            console.error('PDF generation error:', error);
            alert('Error generating PDF: ' + error.message);
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    });
</script>
@endsection
