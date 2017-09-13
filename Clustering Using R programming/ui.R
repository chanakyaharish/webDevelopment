myData <- read.csv("test.csv", header=TRUE)
nums <- sapply(myData, is.numeric)

myData <- myData[,nums]
myData$WMO.Station.Number <- NULL
myData <- myData[, -grep("Footnotes", colnames(myData))]

shinyUI(pageWithSidebar(
  headerPanel('Assignment6- k-means clustering'),
  
  sidebarPanel(
    selectInput('xcol', 'X Variable', names(myData)),
    selectInput('ycol', 'Y Variable', names(myData),
                selected=names(myData)[[2]]),
    numericInput('clusters', 'Cluster count', 3,
                 min = 1, max = 9),
    checkboxInput('header', 'No of Clusters', FALSE)      # Initially set to FALSE
  ),
  mainPanel(
    plotOutput('plot1'),
    textOutput('plot2')
  )
))

