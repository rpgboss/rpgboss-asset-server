package models;

import javax.imageio.IIOImage;
import javax.imageio.ImageIO;
import javax.imageio.ImageWriteParam;
import javax.imageio.ImageWriter;
import javax.imageio.stream.FileImageOutputStream;
import java.awt.*;
import java.awt.image.BufferedImage;
import java.io.*;
import java.util.ArrayList;
import java.util.List;
import java.util.Random;

public class Resizer {
    public static void resize(File originalImage, File to, int w, int h, boolean keepRatio) {
        try {
            BufferedImage source = ImageIO.read(originalImage);
            int owidth = source.getWidth();
            int oheight = source.getHeight();
            double ratio = (double) owidth / oheight;
            
            int maxWidth = w;
            int maxHeight = h;
            
            if (w < 0 && h < 0) {
                w = owidth;
                h = oheight;
            }
            if (w < 0 && h > 0) {
                w = (int) (h * ratio);
            }
            if (w > 0 && h < 0) {
                h = (int) (w / ratio);
            }
            
            if(keepRatio) {
                h = (int) (w / ratio);
                if(h > maxHeight) {
                    h = maxHeight;
                    w = (int) (h * ratio);
                }
                if(w > maxWidth) {
                    w = maxWidth;
                    h = (int) (w / ratio);
                }
            }

            String mimeType = "image/jpeg";
            if (to.getName().endsWith(".png")) {
                mimeType = "image/png";
            }
            if (to.getName().endsWith(".gif")) {
                mimeType = "image/gif";
            }

            // out
            BufferedImage dest = new BufferedImage(w, h, BufferedImage.TYPE_INT_RGB);
            Image srcSized = source.getScaledInstance(w, h, Image.SCALE_SMOOTH);
            Graphics graphics = dest.getGraphics();
            graphics.setColor(Color.WHITE);
            graphics.fillRect(0, 0, w, h);
            graphics.drawImage(srcSized, 0, 0, null);
            ImageWriter writer = ImageIO.getImageWritersByMIMEType(mimeType).next();
            ImageWriteParam params = writer.getDefaultWriteParam();
            FileImageOutputStream toFs = new FileImageOutputStream(to);
            try {
                writer.setOutput(toFs);
                IIOImage image = new IIOImage(dest, null, null);
                writer.write(null, image, params);
                toFs.flush();
            }
            finally {
                toFs.close();
            }
            writer.dispose();
        } catch (Exception e) {
            throw new RuntimeException(e);
        }

    }
}