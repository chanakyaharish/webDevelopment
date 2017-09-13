palette(c("#E41A1C", "#377EB8", "#4DAF4A", "#984EA3",
          "#FF7F00", "#FFFF33", "#A65628", "#F781BF", "#999999"))
myData <- read.csv("test.csv")
nums <- sapply(myData, is.numeric)
myData <- myData[,nums]
myData[myData < -2000] <- 0
myData[myData > 88888] <- 0
myData[myData < 0] <- 0
myData[myData > 500] <- 500


checkboxInput('header', 'No of Clusters', TRUE)

shinyServer(function(input, output, session) {
  
  
  # Combine the selected variables into a new data frame
  selectedData <- reactive({                                # Reactive acts as "event-listener" 
    myData[, c(input$xcol, input$ycol)]                     # listening for changes 
  })
  
  clusters <- reactive({
    kmeans(selectedData(), input$clusters)
  })
  
  output$plot1 <- renderPlot({
    par(mar = c(5.1, 4.1, 0, 1))
   # if(input$header)                                        # If checkbox is 'checked' 
      plot(selectedData(),
           col = clusters()$cluster,
           pch = 20, cex = 3
      )
    #else
     # plot(selectedData(),
      #     col = 12,
       #    pch = 20, cex = 3
      #)
    points(clusters()$centers, pch = 4, cex = 4, lwd = 4)
    print(clusters()$centers)
    print(clusters()$size)
  })
  output$plot2 <- renderText({
    
    str2 <- c("Centers are: ",clusters()$centers)
    str2
  })
  
  
  
})
