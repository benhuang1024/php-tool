# Sample Markdown Document 

Text Block Formatting
---
  Text block with continuations and hard break spaces[^hard-break-spaces],  
            which are two or more trailing spaces
        at the end of the line. 
        
  Footnote definitions can be arranged within the document[^1].      
  
[^hard-break-spaces]: Hard break spaces are two or more trailing spaces on a line that is part of a paragraph text block

## Unordered List Formatting  

* list item 1
list item 1 lazy continuation text  
        with hard break spaces
        
     Contained child text block of bullet list item

* list item 2
list item 2 lazy continuation text  
        with hard break spaces

      Contained child text block of bullet list item

## Ordered List Formatting

1. Ordered list item 1
list item 1 lazy continuation text  
        with hard break spaces

       Contained child text block of bullet list item

1. Ordered list item 2
list item 1 lazy continuation text  
        with hard break spaces

       Contained child text block of bullet list item

[1]: http://example.com  "Example"

[^1]: Footnote text that will be reformatted as a text block  
with continuations and hard break spaces handled  
     according to preferences. Additionally, these elements can be moved within the 
 document: top of document, group with first, group with last or bottom of document. 

## Block Quote Formatting ##

>block quote text with irregular formatting  
>    that will reflow to margins, respecting
>   hard breaks
>>nested block quote
>>nested block quote continuation
>>with hard break spaces  
>>this is a new line

[2]: http://example.com  "Example"

[3]: http://example.com  "Example"

Term 1
:   Definition

| Left Aligned|Right Aligned|Centered
|:-----------|----------:|:-----:
| Row 1 Cell 1 Text|Row 1 Cell 2 Table Cell Text|Row 1 Cell 3 Centered Text
| Row 2 Cell 1 |Row 2 Cell 2|Row 2 Cell 3 Short
[Table Caption]

### Code Fence Formatting

```php
      class Dummy {
          private final int intValue;

          public Dummy(int intValue) {
              this.intValue = intValue;
          }
      }
```

### Verbatim Formatting

      class Dummy {
          private final int intValue;

          public Dummy(int intValue) {
              this.intValue = intValue;
          }
      }

