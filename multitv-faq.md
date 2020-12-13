# FAQ for Page based on MultiTV

### 1. Install MultiTV from Extras Module

### 2. Add config for FAQ
- Create file faq.config.inc.php in folder /assets/tvs/multitv/configs/
```php
<?php
$settings['display'] = 'vertical';
$settings['fields'] = array(
    'question' => array(
        'caption' => 'Question',
        'type' => 'text',
    ),
    'answer' => array(
        'caption' => 'Answer',
        'type' => 'richtext'
    )
);
$settings['templates'] = array(
    'outerTpl' => '<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [ [+wrapper+] ]
    }
    </script>',
    'rowTpl' => '{
        "@type": "Question",
        "name": "[+question+]",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "[+answer+]"
        }
      }[+row.class:ne=`last`:then=`,`+] 
	'
);
$settings['configuration'] = array(
    'enablePaste' => false,
    'enableClear' => false
);
```

### 3. Add TV with name faq and type multitv

### 4. Use for JSON-LD
```html
[[multiTV? &tvName=`faq` &docid=`[*id*]` &display=`all` ]]
```

### 5. Use for HTML Output
```html
[[multiTV?
&tvName=`faq`
&docid=`[*id*]`
&outerTpl=`@CODE:<h2>Häufig gestellte Fragen</h2><div class="faqs">((wrapper))</div>`
&rowTpl=`@CODE:<div class="faq"><h3>((question))</h3><div>((answer))</div></div><hr>`
&display=`all`
]]
```

##Notice
((tags)) - need use only for old site where used PHx plugin,[+tags+] - on new sites without PHx. I recommend not use PHx it slows down the site 