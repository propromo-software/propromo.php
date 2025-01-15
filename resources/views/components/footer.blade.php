<footer class="py-6 mt-auto border-t bg-primary-blue/5 border-primary-blue/10">
    <div class="container px-8 mx-auto">
        <div class="flex flex-col justify-between items-center space-y-4 md:flex-row md:space-y-0">
            <div class="flex gap-4 items-center text-sm text-primary-blue/70">
                <span>© {{ date('Y') }} Propromo. All rights reserved.</span>
                <a 
                    href="https://github.com/propromo-software" 
                    target="_blank" 
                    rel="noopener noreferrer" 
                    class="inline-flex items-center transition-colors duration-200 hover:text-primary-blue"
                >
                    <sl-icon style="font-size: 16px; margin-top: -1px;" name="github"></sl-icon>
                    <span class="ml-1">GitHub</span>
                </a>
            </div>
            
            <x-breadcrumbs :route="$route" location="footer" />
        </div>
    </div>
</footer>
