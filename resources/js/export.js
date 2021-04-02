//import html2canvas from "html2canvas";
import html2canvas from 'html2canvas';
//import VueHtml2Canvas from 'vue-html2canvas';
import jsPDF from "jspdf";

export default class Exporter {
  constructor(charts) {
    this.charts = charts;
    this.doc = new jsPDF("landscape", "px", "letter");
  }
  export_pdf() {
    return new Promise((resolve, reject) => {
      try {
        this.charts.forEach(async (chart, index, charts) => {
          this.doc = await this.create_page(chart, this.doc, index);
          if (index === charts.length - 1) resolve(this.doc); // this.doc.save("charts.pdf"); // <-- at the end of the loop; display PDF
        });
      } catch (error) {
        reject(error);
      }
    });
  }

  async create_page(chart, doc, index) {
    const canvas = await html2canvas(chart, {
      scrollY: -window.scrollY,
      scale: 1 // <-- this is to increase the quality. don't raise this past 5 as it doesn't get much better and just takes longer to process
    });
    const image = canvas.toDataURL("image/jpeg", 1.0),
      pageWidth = doc.internal.pageSize.getWidth() - 100,
      pageHeight = doc.internal.pageSize.getHeight() - 100,
      ratio = (pageWidth - 100) / pageWidth,
      canvasWidth = pageWidth * ratio,
      canvasHeight = pageHeight * ratio,
      marginX = (pageWidth - canvasWidth) / 2,
      marginY = (pageHeight - canvasHeight) / 2;
      //this.$page.props.user.first_name
    doc.addPage();
    doc.setPage(index+1);
    doc.addImage(image, "JPEG", marginX, marginY, canvasWidth, canvasHeight);

    return doc;
  }
}