# TOC for Content
simple solution for TOC, with additional button scroll to top 

## Install

- Download this repositoty
- Upload zip archive to Extas module and run

## Params

#### &lStart 
- Beginner Level - Beginner Heading Level (H1 - H6)
- Dafault: 2

#### &$lEnd
- Ending level - ending heading level (H1 - H6)
- Dafault: 3

#### &tocTitle 
- Title of TOC Block and Name of Button on sidebar (can remove in template)
- Dafault: Contents 

#### &tocClass
- Class of TOC Block
- Dafault: toc
- For sidebar used prefix side_,  side_toc

#### &tocAnchorType
- Anchor type - different options for generating an anchor name. 
  - 1 - transliteration, 
  - 2 - numbering
- Default: 1

#### &table_name
- will be used for the first type of anchors. The tables of the TransAlias plugin are used
- Default: common
- Options: common,russian,dutch,german,czech,utf8,utf8lowercase

#### &tocAnchorLen    
- Maximum anchor length - used for transliteration and limits the length of the anchor name
- Default: 0  (no limmits)

#### &content
- You can add any information for TOC, not only default content
- Default: content (can use chunk) 

#### &contentTpl
- Template for content block
```html
    <div class="[+class+]">
        <span class="title">[+title+]</span>
        [+items+]
    </div>
    [+content+]
```

#### &sidebarTpl
- Template for sidebar block
```html
     <div class="sidebar-block side_[+class+]" id="leftSidebar">
        <div class="sidebar-block__holder">
            <button type="button" id="leftSidebarToggler">[+title+]</button>
            [+items+]
        </div>
    </div>
```

#### &show
- Type of show blocks on page
- Default: 0
- Types: 
    - 0 (hide)
    - 1 (show content)
    - 2 (show content and sidebar)
- Recommend use with tv toc for set in document

#### $upbutton
- Show scrool to top button
- Default: 0
- Types: 0,1

#### $css
- use CSS (use can disable and write own CSS, or copy to main css)
- Default: 1
- Types: 0,1

## Howe use: 
### Simple
Put this code before content block:
```HTML
[[toc? &show=`1` &upbutton=`1`]]
``` 

### With TV (need add tv toc for template)
Put this code before content block:
```HTML
[[toc? &show=`[*toc*]` &upbutton=`1`]]
``` 

### Use with Custom content block
```[[toc? &show=`[*toc*]` &content=`content_chunk_name`]]```

content_chunk_name:

```html
[*content*]
[[multiTV?
    &tvName=`faq`
    &docid=`[*id*]`
    &outerTpl=`@CODE:<h2>Häufig gestellte Fragen</h2><div class="faqs">((wrapper))</div>`
    &rowTpl=`@CODE:<div class="faq"><h3>((question))</h3><div>((answer))</div></div><hr>`
    &display=`all`
]]
```
